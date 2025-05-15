<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Exception;

class CreditSafeService
{
    protected $baseUrl = 'https://connect.creditsafe.com/v1';
    protected $username;
    protected $password;
    protected $tempToken = null; // Added as in-memory fallback
    protected $tempTokenExpiry = null; // Track when the in-memory token expires

    public function __construct()
    {
        $this->username = env('CREDITSAFE_USERNAME', 'reyad.bouzeboudja@saamp.com');
        $this->password = env('CREDITSAFE_PASSWORD', ';*#L!G5J^KY5V*o$G-[cT#');
    }

    /**
     * Obtenir le token d'authentification, avec mise en cache pour éviter des appels répétitifs
     * Utilise un mécanisme de fallback en mémoire si le cache échoue
     *
     * @return string
     * @throws Exception
     */
    protected function getAuthToken()
    {
        // Check if we have a valid in-memory token first
        if ($this->tempToken && $this->tempTokenExpiry && $this->tempTokenExpiry > now()) {
            return $this->tempToken;
        }

        // Try to get from cache if possible
        try {
            if (Cache::has('creditsafe_token')) {
                return Cache::get('creditsafe_token');
            }
        } catch (Exception $e) {
            // Log cache read error but continue with token retrieval
            Log::warning('Impossible de lire le token CreditSafe depuis le cache', [
                'message' => $e->getMessage()
            ]);
        }

        // Get a new token
        try {
            $response = Http::post($this->baseUrl . '/authenticate', [
                'username' => $this->username,
                'password' => $this->password
            ]);

            if ($response->successful()) {
                $token = $response->json()['token'];
                
                // Try to store in cache, but don't fail if it doesn't work
                try {
                    Cache::put('creditsafe_token', $token, now()->addMinutes(55));
                } catch (Exception $e) {
                    // Log but continue with in-memory fallback
                    Log::warning('Échec de mise en cache du token CreditSafe, utilisation du fallback en mémoire', [
                        'message' => $e->getMessage()
                    ]);
                }
                
                // Store in memory as fallback
                $this->tempToken = $token;
                $this->tempTokenExpiry = now()->addMinutes(55);
                
                return $token;
            } else {
                Log::error('Échec d\'authentification CreditSafe', [
                    'status' => $response->status(),
                    'response' => $response->json()
                ]);
                throw new Exception('Échec d\'authentification à l\'API CreditSafe');
            }
        } catch (Exception $e) {
            Log::error('Exception lors de l\'authentification CreditSafe', [
                'message' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Effectuer une requête API vers CreditSafe
     * Avec retry en cas d'échec d'authentification
     *
     * @param string $method
     * @param string $endpoint
     * @param array $params
     * @param bool $retried
     * @return array
     * @throws Exception
     */
    protected function makeRequest($method, $endpoint, $params = [], $retried = false)
    {
        try {
            $token = $this->getAuthToken();
            
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $token,
                'Accept' => 'application/json',
            ])->$method($this->baseUrl . $endpoint, $params);

            if ($response->successful()) {
                return $response->json();
            } 
            
            // Check if token is expired (401)
            if ($response->status() === 401 && !$retried) {
                // Force token refresh
                $this->tempToken = null;
                // Clear cache if possible
                try {
                    Cache::forget('creditsafe_token');
                } catch (Exception $e) {
                    // Just log and continue
                    Log::warning('Impossible d\'effacer le token expiré du cache', [
                        'message' => $e->getMessage()
                    ]);
                }
                
                // Retry once with fresh token
                return $this->makeRequest($method, $endpoint, $params, true);
            }
            
            // Other errors
            Log::error('Échec de requête CreditSafe', [
                'endpoint' => $endpoint,
                'status' => $response->status(),
                'response' => $response->json()
            ]);
            throw new Exception('Échec de la requête à l\'API CreditSafe: ' . $response->status());
        } catch (Exception $e) {
            Log::error('Exception lors de la requête CreditSafe', [
                'endpoint' => $endpoint,
                'message' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Rechercher une entreprise par SIRET
     *
     * @param string $siret
     * @return array
     */
    public function searchCompanyBySiret($siret)
    {
        // Sanitize SIRET: trim whitespace and remove any non-numeric characters
        $sanitizedSiret = preg_replace('/[^0-9]/', '', trim($siret));
        
        // Check if SIRET is valid (should be 14 digits for a French SIRET)
        if (strlen($sanitizedSiret) !== 14) {
            Log::warning('SIRET format potentiellement invalide', [
                'siret_original' => $siret,
                'siret_sanitized' => $sanitizedSiret,
                'length' => strlen($sanitizedSiret)
            ]);
        }
        
        return $this->makeRequest('get', '/companies', [
            'countries' => 'FR',
            'regNo' => $sanitizedSiret,
            'status' => 'Active'
        ]);
    }

    /**
     * Obtenir les informations détaillées d'une entreprise par son ID
     *
     * @param string $companyId
     * @return array
     */
    public function getCompanyDetails($companyId)
    {
        return $this->makeRequest('get', '/companies/' . $companyId . '?language=fr&template=full');
    }

    /**
     * Générer un rapport PDF pour une entreprise
     *
     * @param string $companyId
     * @return array
     */
    public function generateCompanyReport($companyId)
    {
        return $this->makeRequest('get', '/companies/' . $companyId . '/creditreport/pdf?language=fr&template=full');
    }

    /**
     * Processus complet pour obtenir les informations d'une entreprise à partir de son SIRET
     *
     * @param string $siret
     * @return array|null
     */
    public function getCompanyInfoBySiret($siret)
    {
        try {
            // Étape 1: Recherche par SIRET (sera sanitisé dans searchCompanyBySiret)
            $searchResult = $this->searchCompanyBySiret($siret);
            
            // Vérifier si des résultats ont été trouvés
            if (empty($searchResult['companies']) || count($searchResult['companies']) === 0) {
                Log::info('Aucune entreprise trouvée pour le SIRET', [
                    'siret_original' => $siret,
                    'siret_sanitized' => preg_replace('/[^0-9]/', '', trim($siret))
                ]);
                return null;
            }
            
            // Étape 2: Récupérer l'ID de la première entreprise trouvée
            $companyId = $searchResult['companies'][0]['id'];
            
            // Étape 3: Obtenir les détails complets
            return $this->getCompanyDetails($companyId);
        } catch (Exception $e) {
            Log::error('Erreur lors de la récupération des informations de l\'entreprise', [
                'siret' => $siret,
                'siret_sanitized' => preg_replace('/[^0-9]/', '', trim($siret)),
                'message' => $e->getMessage()
            ]);
            return null;
        }
    }

    /**
     * Obtenir l'ID de l'entreprise à partir de son SIRET
     *
     * @param string $siret
     * @return string|null
     */
    public function getCompanyIdBySiret($siret)
    {
        try {
            // Siret will be sanitized in searchCompanyBySiret method
            $searchResult = $this->searchCompanyBySiret($siret);
            
            if (empty($searchResult['companies']) || count($searchResult['companies']) === 0) {
                Log::info('Aucun résultat trouvé pour le SIRET', [
                    'siret_original' => $siret,
                    'siret_sanitized' => preg_replace('/[^0-9]/', '', trim($siret))
                ]);
                return null;
            }
            
            return $searchResult['companies'][0]['id'];
        } catch (Exception $e) {
            Log::error('Erreur lors de la récupération de l\'ID de l\'entreprise', [
                'siret' => $siret,
                'message' => $e->getMessage()
            ]);
            return null;
        }
    }
}