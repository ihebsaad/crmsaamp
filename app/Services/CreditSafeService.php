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
protected function makeRequest($method, $endpoint, $params = [], $retried = false, $additionalHeaders = [])
{
    try {
        $token = $this->getAuthToken();
        
        $headers = [
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
        ];
        
        // Fusionner avec les en-têtes additionnels si fournis
        if (!empty($additionalHeaders)) {
            $headers = array_merge($headers, $additionalHeaders);
        }
        
        $response = Http::timeout(15)->withHeaders($headers)->$method($this->baseUrl . $endpoint, $params);

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
            return $this->makeRequest($method, $endpoint, $params, true, $additionalHeaders);
        }
        
        // Other errors
        Log::error('Échec de requête CreditSafe', [
            'endpoint' => $endpoint,
            'method' => $method,
            'params' => $params,
            'status' => $response->status(),
            'response' => $response->body()
        ]);
        throw new Exception('Échec de la requête à l\'API CreditSafe: ' . $response->status());
    } catch (Exception $e) {
        Log::error('Exception lors de la requête CreditSafe', [
            'endpoint' => $endpoint,
            'method' => $method,
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

     /*
    public function generateCompanyReport($companyId)
    {
        return $this->makeRequest('get', '/companies/' . $companyId . '?language=fr&template=full');
    }*/
    /*
    public function generateCompanyReport($companyId)
    {
        try {
            // Première vérification pour confirmer que l'entreprise existe
            $this->getCompanyDetails($companyId);
            
            // Utilisation de l'endpoint `/companies/{id}/report`
            // avec l'en-tête Accept: application/pdf pour obtenir le PDF
            return $this->makeRequest('get', '/companies/' . $companyId  , [
                'language' => 'fr',
                'template' => 'full'
            ], false, [
                'Accept' => 'application/json+pdf'
            ]);
        } catch (Exception $e) {
            // Vérifier les cas d'erreur spécifiques
            if (strpos($e->getMessage(), '404') !== false) {
                Log::error('Rapport PDF non disponible pour cette entreprise', [
                    'companyId' => $companyId
                ]);
            }
            throw $e;
        }
    }
*/



    /**
     * Génère et récupère un rapport d'entreprise de CreditSafe
     * @param string $companyId ID de l'entreprise dans CreditSafe
     * @param bool $rawPdf Si true, retourne directement le contenu binaire du PDF; si false, retourne la réponse JSON
     * @return mixed Le contenu PDF ou un tableau contenant les données JSON
     * @throws Exception En cas d'erreur lors de la requête
     */
    public function generateCompanyReport($companyId, $rawPdf = true) 
    {
        try {
            // Vérification préalable que l'entreprise existe
            $this->getCompanyDetails($companyId);
            
            // URL de l'API - en utilisant la constante CREDITSAFE_API_URL ou la méthode makeRequest
            if ($rawPdf) {
                // Utilisation directe de curl comme dans votre premier exemple
                $pdfUrl = defined('CREDITSAFE_API_URL') ? CREDITSAFE_API_URL : $this->baseUrl;
                $pdfUrl .= "/companies/{$companyId}?language=fr&template=full";
                
                $ch = curl_init($pdfUrl);
                curl_setopt_array($ch, [
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_HTTPHEADER     => [
                        "Authorization: Bearer " . $this->getAuthToken(),
                        "Accept: application/pdf"
                    ],
                ]);
                
                $pdfContent = curl_exec($ch);
                $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                curl_close($ch);
                
                if ($httpCode !== 200) {
                    throw new Exception("Erreur lors du téléchargement du PDF (HTTP {$httpCode})");
                }
                
                return $pdfContent; // Contenu binaire du PDF
            } else {
                // Utilisation de la méthode makeRequest comme dans votre implementation originale
                return $this->makeRequest('get', '/companies/' . $companyId, [
                    'language' => 'fr',
                    'template' => 'full'
                ], false, [
                    'Accept' => 'application/json+pdf'
                ]);
            }
        } catch (Exception $e) {
            // Gestion des erreurs spécifiques
            if (strpos($e->getMessage(), '404') !== false) {
                Log::error('Rapport PDF non disponible pour cette entreprise', [
                    'companyId' => $companyId
                ]);
            }
            throw $e;
        }
    }

    /**
     * Télécharge un rapport d'entreprise pour un client spécifique
     */
    public function downloadCompanyReport($clientId)
    {
        try {
            if (!$clientId) {
                return response()->json([
                    'success' => false,
                    'message' => 'ID client non fourni'
                ], 400);
            }
            
            $client = CompteClient::findOrFail($clientId);
            
            if (!$client->siret) {
                Log::error('Le SIRET n\'est pas disponible pour ce client');
                return back()->withErrors(['msg' => 'Le SIRET n\'est pas disponible pour ce client']);
            }
            
            // Obtenir l'ID de l'entreprise
            $companyId = $this->creditSafeService->getCompanyIdBySiret($client->siret);
            
            if (!$companyId) {
                Log::error('Aucune entreprise trouvée pour ce SIRET');
                return back()->withErrors(['msg' => 'Aucune entreprise trouvée pour ce SIRET']);
            }
            
            // Générer le rapport en mode PDF brut (true)
            $pdfContent = $this->creditSafeService->generateCompanyReport($companyId, true);
            
            // Nom du fichier
            $companyName = trim($client->Nom) ?? $client->Nom;
            $filename = 'rapport_creditsafe_' . strtolower(preg_replace('/[^a-zA-Z0-9]/', '_', $companyName)) . '.pdf';
            
            // Renvoyer le PDF comme réponse de téléchargement
            return response($pdfContent)
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
        } catch (Exception $e) {
            Log::error('Erreur lors de la génération du rapport', [
                'message' => $e->getMessage()
            ]);
            
            return back()->withErrors(['msg' => 'Erreur lors de la génération du rapport']);
        }
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