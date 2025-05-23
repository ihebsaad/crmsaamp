<?php

namespace App\Http\Controllers;

use App\Models\CompteClient;
use App\Services\CreditSafeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Exception;

class CreditSafeController extends Controller
{
    protected $creditSafeService;

    public function __construct(CreditSafeService $creditSafeService)
    {
        $this->creditSafeService = $creditSafeService;
    }

    /**
     * Récupérer les informations de l'entreprise pour affichage dans une popup
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCompanyInfo($clientId)
    {
        try {
            //$clientId = $request->input('client_id');
            
            if (!$clientId) {
                return response()->json([
                    'success' => false,
                    'message' => 'ID client non fourni'
                ], 400);
            }
            
            $client = CompteClient::findOrFail($clientId);
            
            if (!$client->siret) {
                return response()->json([
                    'success' => false,
                    'message' => 'Le SIRET n\'est pas disponible pour ce client'
                ], 404);
            }
            
            $companyInfo = $this->creditSafeService->getCompanyInfoBySiret($client->siret);
            
            if (!$companyInfo) {
                return response()->json([
                    'success' => false,
                    'message' => 'Aucune information trouvée pour ce SIRET'
                ], 404);
            }
            
            return response()->json([
                'success' => true,
                'data' => $companyInfo
            ]);
        } catch (Exception $e) {
            Log::error('Erreur lors de la récupération des informations de l\'entreprise', [
                'message' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Une erreur est survenue lors de la récupération des informations: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Générer et télécharger un rapport d'entreprise
     *
     * @param Request $request
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     */
    public function downloadCompanyReport($clientId)
    {
        try {
            //$clientId = $request->input('client_id');
            
            if (!$clientId) {
                return response()->json([
                    'success' => false,
                    'message' => 'ID client non fourni'
                ], 400);
            }
            
            $client = CompteClient::findOrFail($clientId);
            
            if (!$client->siret) {

                Log::error('Le SIRET n\'est pas disponible pour ce client');
                return back()->withErrors(['msg' =>'Le SIRET n\'est pas disponible pour ce client']);
                /*
                return response()->json([
                    'success' => false,
                    'message' => 'Le SIRET n\'est pas disponible pour ce client'
                ], 404);*/
            }
            
            // Obtenir l'ID de l'entreprise
            $companyId = $this->creditSafeService->getCompanyIdBySiret($client->siret);
            
            if (!$companyId) {
                Log::error('Aucune entreprise trouvée pour ce SIRET');
                return back()->withErrors(['msg' => 'Aucune entreprise trouvée pour ce SIRET']);

                /*
                return response()->json([
                    'success' => false,
                    'message' => 'Aucune entreprise trouvée pour ce SIRET'
                ], 404);*/
            }
            
            // Générer le rapport
            $report = $this->creditSafeService->generateCompanyReport($companyId);
            //dd($report);
            /*
            if (!isset($report['pdfReportStream'])) {
                return back()->withErrors(['msg' => 'Impossible de générer le rapport PDF']);
            }
            
            // Décoder le contenu PDF (généralement encodé en base64)
            $pdfContent = base64_decode($report['pdfReportStream']);
            */
            // Nom du fichier
            $companyName = trim($client->Nom) ?? $client->Nom;
            $filename = 'rapport_creditsafe_' . strtolower(preg_replace('/[^a-zA-Z0-9]/', '_', $companyName)) . '.pdf';
            
            // Renvoyer le PDF comme réponse de téléchargement
            return response($report)
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
        } catch (Exception $e) {

            Log::error('Erreur lors de la génération du rapport', [
                'message' => $e->getMessage()
            ]);
            
            return back()->withErrors(['msg' =>'Erreur lors de la génération du rapport']); ;

        }
    }

    /**
     * Rendu de la vue popup avec les informations de l'entreprise
     *
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function showCompanyInfoPopup(Request $request)
    {
        try {
            $clientId = $request->input('client_id');
            
            if (!$clientId) {
                return redirect()->back()->with('error', 'ID client non fourni');
            }
            
            $client = CompteClient::findOrFail($clientId);
            
            if (!$client->siret) {
                return redirect()->back()->with('error', 'Le SIRET n\'est pas disponible pour ce client');
            }
            
            $companyInfo = $this->creditSafeService->getCompanyInfoBySiret($client->siret);
            
            if (!$companyInfo) {
                return redirect()->back()->with('error', 'Aucune information trouvée pour ce SIRET');
            }
            
            return view('creditsafe.company_info', [
                'companyInfo' => $companyInfo,
                'client' => $client
            ]);
        } catch (Exception $e) {
            Log::error('Erreur lors de l\'affichage des informations de l\'entreprise', [
                'message' => $e->getMessage()
            ]);
            
            return redirect()->back()->with('error', 'Une erreur est survenue: ' . $e->getMessage());
        }
    }
}