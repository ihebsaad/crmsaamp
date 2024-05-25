<?php

namespace App\Services;

use DB;


class TalendService
{

	public static function update_stock($famille1,$famille2,$famille3,$type,$alliage)
	{
		// \Log::info('Fam1: '.$famille1.' Fam2: '.$famille2.' Fam3: '.$famille3.' Type: '.$type.' All : '.$alliage);
		// Chemin du fichier PROD.properties
		$fichier = '/var/www/mysaamp/Talend/Job_Talend_interface/STOCKS/MAJ_STOCK_AS400/commande_as400_vers_mysaamp/maj_stock_as400_0_1/contexts/PROD.properties';

		// Contenu actuel du fichier
		$contenu = file_get_contents($fichier);
        $produit=  DB::table('type_famille')->where('type_id',$type)->where('fam1_id',$famille1)->where('fam2_id',$famille2)->where('fam3_id',$famille3)->first();
		// \Log::info(" Prod id : ".$produit->id);
        //$code_all=  DB::table('alliage')->where('ALLIAGE_IDENT',$alliage)->first()->code_metal_as400 ?? "";

		$result = DB::select("SELECT group_concat(al.AS400CALL SEPARATOR ''' , ''' ) AS code_metal_as400 FROM Alliage_AS400 al WHERE al.alliage_ident = ?", [$alliage]);
		// Vérifiez si des résultats ont été renvoyés
		if (!empty($result)) {
			// Obtenez le code AS400 à partir des résultats
			$code_all = $result[0]->code_metal_as400;
			//$code_all = "'".$code_all."'";
			$code_all = trim($code_all);


		} else {
			$code_all = ""; // Affectez une valeur par défaut si aucun résultat n'est retourné
		}

		//\Log::info(" - fam1_as400 ".$produit->fam1_as400." - fam2_as400 ".$produit->fam2_as400. " - fam3_as400 ".$produit->fam3_as400." - Code_all :" .$code_all);
		// Mettre à jour les valeurs
		$contenu = TalendService::updatePropertyValue($contenu, 'code_all', $code_all);
		$contenu = TalendService::updatePropertyValue($contenu, 'famille1', $produit->fam1_as400);
		$contenu = TalendService::updatePropertyValue($contenu, 'famille2', $produit->fam2_as400);
		$contenu = TalendService::updatePropertyValue($contenu, 'famille3', $produit->fam3_as400);

		// Écrire le contenu mis à jour dans le fichier
		file_put_contents($fichier, $contenu);

		// \Log::info("Le fichier PROD.properties a été mis à jour avec succès.");
		TalendService::executeTalendScript();
	}

	private static function updatePropertyValue($contenu, $property, $value)
	{
        //$value = (empty($value)) ? '' : $value;

		// Mettre à jour la valeur de la propriété dans le fichier
		return preg_replace("/$property=.*$/m", "$property=$value", $contenu);
	}


	public static function executeTalendScript() {
        // Chemin du script Talend
        $scriptPath = '/var/www/mysaamp/Talend/Job_Talend_interface/STOCKS/MAJ_STOCK_AS400/MAJ_STOCK_AS400_run.sh';

        // Vérifier si le script existe
        if (file_exists($scriptPath)) {
            // Exécuter le script
            $output = shell_exec("sh $scriptPath 2>&1");

            // Afficher la sortie (utile pour le débogage)
            //echo nl2br("Script Output:\n" . $output);
			// \Log::info(nl2br("Script Output:\n" . $output));
            \Log::info("Le script Talend a été exécuté avec succès.");
        } else {
            \Log::error("Erreur : Le script Talend n'a pas été trouvé.");
        }
    }

}
