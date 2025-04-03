<?php

namespace App\Services;

use DB;
use Illuminate\Support\Facades\Http;
use App\Models\CompteClient;
use PDO;

class GEDService
{

	public static function getToken()
	{

		$authUrl = config('ged.url');
		$user = config('ged.user');
		$pass = config('ged.pass');

		// Données d'authentification
		$authData = json_encode(
			array(
				'login' => $user,
				'password' => $pass
			)
		);

		// Initialisation de cURL pour l'authentification
		$curlAuth = curl_init();

		// Configuration de cURL pour l'authentification
		curl_setopt_array(
			$curlAuth,
			array(
				CURLOPT_CUSTOMREQUEST => 'POST',
				CURLOPT_URL => $authUrl,
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_POSTFIELDS => $authData,
				CURLOPT_HTTPHEADER => array(
					'Content-Type: application/json'
				),
				CURLOPT_SSL_VERIFYPEER => false
			)
		);

		// Exécution de la requête cURL pour l'authentification
		$authResponse = curl_exec($curlAuth);
		$authErr = curl_error($curlAuth);

		// Fermeture de la session cURL pour l'authentification
		curl_close($curlAuth);

		// Vérification des erreurs cURL pour l'authentification
		if ($authErr) {
			\Log::error("Erreur auth GED cURL : " . $authErr);
		} else {
			// Décodage de la réponse d'authentification
			$authResponseData = json_decode($authResponse, true);

			// Vérification de l'authentification réussie et récupération du token
			if (isset($authResponseData['data']['authToken'])) {
				$authToken = $authResponseData['data']['authToken'];
				return $authToken;
			} else {
				\Log::error("Erreur GED : Authentification échouée, token non reçu.");
			}
		}
	}


	public static function curlExecute($apiUrl)
	{
		$headers = array(
			'Content-Type: application/json',
			'Auth-Token: ' . self::getToken()
		);

		// Initialisation de cURL
		$curl = curl_init();

		// Configuration de cURL
		curl_setopt_array(
			$curl,
			array(
				CURLOPT_CUSTOMREQUEST => 'GET',
				CURLOPT_URL => $apiUrl,
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_HTTPHEADER => $headers,
				CURLOPT_SSL_VERIFYPEER => false
			)
		);

		// Exécution de la requête cURL
		$response = curl_exec($curl);
		$err = curl_error($curl);

		// Fermeture de la session cURL
		curl_close($curl);

		// Vérification des erreurs cURL
		if ($err) {
			\Log::error("Erreur GED  : " . $err);
		} else {
			return $response;
		}
	}


	public static function getItem($itemId)
	{

		// URL de l'API pour visualiser l'élément
		$apiUrl = "https://ged.maileva.com/api/document/$itemId/stream";
/*
		// Exécution de la requête cURL
		//$response = self::curlExecute($apiUrl);


		header('Content-Type: application/pdf');
		return $response;
		*/
		$headers = array(
			'Content-Type: application/json',
			'Auth-Token: ' . self::getToken()
		);

		$curl = curl_init();
		curl_setopt_array($curl, array(
			CURLOPT_CUSTOMREQUEST => 'GET',
			CURLOPT_URL => $apiUrl,
			CURLOPT_HEADER => true,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_HTTPHEADER => $headers,
			CURLOPT_SSL_VERIFYPEER => false
		));
		$response = curl_exec($curl);
		$err = curl_error($curl);

		$headerSize = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
		$header = substr($response, 0, $headerSize);
		$body   = substr($response, $headerSize);
		curl_close($curl);

		if ($err) {
			echo "Erreur cURL : " . $err;
		} else {
			// Récupération du Content-Type depuis l'hedear
			$contentType = 'application/octet-stream'; // Valeur par défaut si rien
			if (preg_match('/Content-Type:\s*([^;\r\n]+)/i', $header, $matches)) {
				$contentType = trim($matches[1]);
			}

			$data['type']=$contentType;
			$data['body']=$body;
			return $data;
		}

	}

	public static function editItem($itemId, $attachment, $id, $type)
	{

		// Récupérer le contenu du fichier et le nom du fichier
		$fileName = $attachment->getClientOriginalName();

		$headers = [
			'Content-Type: multipart/form-data',
			'Auth-Token: ' . self::getToken()
		];

		// URL de l'API pour remplacer l'élément
		$apiUrl = "https://ged.maileva.com/api/document/{$itemId}/replace";

		// Construction des données pour l'envoi de fichier
		$postFields = [
			'attachment' => new \CURLFile($attachment->getPathname(), $attachment->getMimeType(), $fileName)
		];

		// Initialisation de cURL
		$curl = curl_init();

		// Configuration de cURL
		curl_setopt_array($curl, [
			CURLOPT_CUSTOMREQUEST => 'POST',
			CURLOPT_URL => $apiUrl,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_POSTFIELDS => $postFields,
			CURLOPT_HTTPHEADER => $headers,
			CURLOPT_SSL_VERIFYPEER => false,
		]);

		// Exécution de la requête cURL
		$response = curl_exec($curl);
		$err = curl_error($curl);

		// Fermeture de la session cURL
		curl_close($curl);

		// Vérification des erreurs cURL
		if ($err) {
			echo "Erreur cURL : " . $err;
		} else {
			// Traitement de la réponse de l'API
			$data = json_decode($response, true);
			if ($data && $data['success'] === true) {
				if ($type == 'client')
					return redirect()->route('compte_client.folder', ['id' => $id])->with(['success' => "Le document a été mis à jour avec succès. "]);
				else
					return redirect()->route('offres.show', $id)->with(['success' => "Le document a été mis à jour avec succès. "]);
			} else {

				if ($type == 'client')
					return redirect()->route('compte_client.folder', ['id' => $id])->withErrors(['msg' => "Erreur lors de la mise à jour du document."]);
				else
					return redirect()->route('offres.show', $id)->withErrors(['msg' => "Erreur lors de la mise à jour du document."]);
			}
		}
	}


	public static function getFolderParent($clientDisplayName)
	{

		// URL de l'API pour récupérer les sous-dossiers
		$apiUrl = "https://ged.maileva.com/api/folder/name/" . $clientDisplayName;
		//$apiUrl = "https://ged.maileva.com/api/folder/name/DOCUMENTS%20OFFRES%20DE%20PRIX/12137/1363";

		//$apiUrl = "https://ged.maileva.com/api/folder/name/DOCUMENTS%20OUVERTURE%20DE%20COMPTE/". $clientDisplayName;

		$response = self::curlExecute($apiUrl);

		// Décodage de la réponse JSON
		$data = json_decode($response, true);

		if (empty($response)) {
			//dd('Aucun dossier trouvé pour le client.');
			return "<p class='aucun'>Aucun dossier trouvé pour le client.</p>";
		} else {
			$responseArray = json_decode($response, true);
			// Conversion JSON a réussi
			if ($responseArray !== null && $responseArray['success'] === true) {
				$parFolderId = $responseArray['data']['id'];
				/*
				$result = self::getFolderContent($parFolderId);
				//$result=self::getFolderList($parFolderId);
				return $result;*/

				$result['folders'] = self::getFolderList($parFolderId);
				$data=self::getFolderContent($parFolderId,1000,1,'','');
				$folderContent=$data['data'] ?? [] ;
				$result['files'] = $folderContent;
				return $result;
			}
		}
	}

	/*
	public static function getFolders($clientDisplayName){

		// URL de l'API pour récupérer les sous-dossiers
		$apiUrl = "https://ged.maileva.com/api/folder/name/" . $clientDisplayName;
		//$apiUrl = "https://ged.maileva.com/api/folder/name/DOCUMENTS%20OUVERTURE%20DE%20COMPTE/". $clientDisplayName;

		$response = self::curlExecute($apiUrl);

		// Décodage de la réponse JSON
		$data = json_decode($response, true);

		if (empty($response)) {
			//dd('Aucun dossier trouvé pour le client.');
			return "<p class='aucun'>Aucun dossier trouvé pour le client.</p>";
		} else {
			$responseArray = json_decode($response, true);
			// Conversion JSON a réussi
			if ($responseArray !== null && $responseArray['success'] === true) {
				$parFolderId = $responseArray['data']['id'];

				$result=self::getFolderList($parFolderId);
				return $result;

			}
		}

	}
*/


	public static function getFolders($clientId)
	{
		// URL de l'API pour récupérer les sous-dossiers
		//$apiUrl = "https://ged.maileva.com/api/folder/name/DOCUMENTS%20OUVERTURE%20DE%20COMPTE";
		$apiUrl = "https://ged.maileva.com/api/folder/find?parentId=1304415&name=$clientId";

		$response = self::curlExecute($apiUrl);
		$compteFolderId = null;
		$headers = array(
			'Content-Type: application/json',
			'Auth-Token: ' . self::getToken()
		);
		// Décodage de la réponse JSON
		$data = json_decode($response, true);

		$clientFolderId = null;

		if ($data !== null && $data['success'] === true) {
			$compteFolderId = $data['data']['id'];
			//dd($data);
			//Vérifier si un dossier pour ce client nommé $id_client existe déjà dans "DOCUMENTS OUVERTURE DE COMPTE" sinon on le créer
			$apiUrl2 = "https://ged.maileva.com/api/folder/find?parent_id=$compteFolderId&name=$clientId";
			$response = self::curlExecute($apiUrl2);

			if ($data !== null && $data['success'] === true) {
				$clientFolderId = $data['data']['id'];
				$result = self::getFolderList($clientFolderId);
				return $result;
			}
		}
	}


	public static function getFolderList($folderId)
	{
		// URL de l'API pour récupérer les sous-dossiers
		$apiUrl = "https://ged.maileva.com/api/folders/list/$folderId";
		$response = self::curlExecute($apiUrl);

		// Décodage de la réponse JSON
		$data = json_decode($response, true);
		\Log::info('getFolderList ' . json_encode($response));

		if ($data !== null && $data['success'] === true) {
			$folders = $data['data'];

			// Trier les dossiers par nom
			usort($folders, function ($a, $b) {
				return strcmp($a['name'], $b['name']);
			});

			return $folders;
		}

		return [];
	}



/*
	public static function getFolderContent($folderId)
	{

		// URL de l'API pour récupérer les sous-dossiers
		$apiUrl = "https://ged.maileva.com/api/document/childrenOf/$folderId?p=$folderId";
		$response = self::curlExecute($apiUrl);

		// Décodage de la réponse JSON
		$data = json_decode($response, true);
		if ($data !== null && $data['success'] === true) {
			\Log::info('getFolderContent ' . json_encode($response));

			return $data['data'];
		}
	}*/

	public static function getFolderContent($folderId,$limit,$page,$searchLot,$month)
	{

		// Détermine si une recherche est active
		$searchActive = ($searchLot !== '' || $month !== '');

		if ($searchActive) {
			$apiLimit = 1000; // Limit élevée pour récup tous les documents
			$apiUrl = "https://ged.maileva.com/api/document/childrenOf/{$folderId}?p={$folderId}&limit={$apiLimit}";
		} else {
			$apiLimit = $limit;
			$apiUrl = "https://ged.maileva.com/api/document/childrenOf/{$folderId}?p={$folderId}&page={$page}&limit={$apiLimit}";
		}


		$response = self::curlExecute($apiUrl);
		$data = json_decode($response, true);
		$result=array();
		$documents = isset($data['data']) ? $data['data'] : [];

		if ($searchActive) {
			// Filtrage par numéro de lot
			if ($searchLot !== '') {
				$documents = array_filter($documents, function($doc) use ($searchLot) {
					return stripos($doc['name'], $searchLot) !== false;
				});
			}
			// Filtrage par mois
			if ($month !== '') {
				$documents = array_filter($documents, function($doc) use ($month) {
					if (preg_match('/_(\d{2})_(\d{2})_(\d{4})/', $doc['name'], $matches)) {
						// $matches[1] correspond au jour, $matches[2] au mois, et $matches[3] à l'année
						return $matches[2] === $month;
					}
					return false;
				});
			}

			// Pagination manuelle sur le tableau filtré
			$totalFiltered = count($documents);
			$result['totalFiltered']=$totalFiltered;
			$totalPages = ceil($totalFiltered / $limit);
			$result['totalPages']=$totalPages;

			// Découpage des documents pour la page actuelle
			$documents = array_slice($documents, ($page - 1) * $limit, $limit);
		} else {
			// Sinon, on utilise la pagination fournie par l'API
			$links = isset($data['links']) ? $data['links'] : [];
			$result['links']=$links;
		}

		$result['data']=$documents;

		\Log::info('folder content : '. json_encode($result));
		return $result;

	}

	// calculer le nombre de fichiers (à tester)
	public static function countFiles($folderId)
	{
		// URL de l'API pour récupérer les sous-dossiers
		$apiUrl = "https://ged.maileva.com/api/document/childrenOf/$folderId?p=$folderId";
		$response = self::curlExecute($apiUrl);

		// Décodage de la réponse JSON
		$data = json_decode($response, true);
		if ($data !== null && $data['success'] === true) {
			\Log::info('getFolderContent ' . json_encode($response));

			return count($data['data']);

		}
	}

	public static function downloadItem($itemId)
	{
		$headers = array(
			'Content-Type: application/json',
			'Auth-Token: ' . self::getToken()
		);

		// URL de l'API pour visualiser l'élément
		$apiUrl = "https://ged.maileva.com/api/document/$itemId/download";

		// Initialisation de cURL
		$curl = curl_init();

		curl_setopt_array(
			$curl,
			array(
				CURLOPT_CUSTOMREQUEST => 'GET',
				CURLOPT_URL => $apiUrl,
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_HTTPHEADER => $headers,
				CURLOPT_SSL_VERIFYPEER => false,
				CURLOPT_HEADER => true
			)
		);

		// Exécution de la requête cURL
		$response = curl_exec($curl);
		$err = curl_error($curl);

		// Fermeture de la session cURL
		curl_close($curl);

		// Vérification des erreurs cURL
		if ($err) {
			\Log::error("Erreur GED download : " . $err);
		} else {
			// Séparation de l'en-tête et du corps de la réponse
			$header_size = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
			$header = substr($response, 0, $header_size);
			$body = substr($response, $header_size);

			// Extraction du nom du fichier à partir des en-têtes
			if (preg_match('/filename="([^"]+)"/', $header, $matches)) {
				$fileName = $matches[1];
			} else {
				$fileName = "downloaded_file";
			}

			// Téléchargement du fichier
			header('Content-Description: File Transfer');
			header('Content-Type: application/octet-stream');
			header('Content-Disposition: attachment; filename="' . $fileName . '"');
			header('Content-Length: ' . strlen($body));
			echo $body;
		}
	}






	// OUVERTURE DE COMPTE
	public static function Account($clientId, $type, $id, $files)
	{
		$client_name=CompteClient::where('cl_ident',$clientId)->first()->Nom ?? '';
		// URL de l'API pour récupérer les sous-dossiers
		$apiUrl = "https://ged.maileva.com/api/folder/name/DOCUMENTS%20OUVERTURE%20DE%20COMPTE";
		$response = self::curlExecute($apiUrl);
		$compteFolderId = null;

		$headers = array(
			'Content-Type: application/json',
			'Auth-Token: ' . self::getToken()
		);
		// Décodage de la réponse JSON
		$data = json_decode($response, true);

		if ($data !== null && $data['success'] === true) {
			$compteFolderId = $data['data']['id'];
		} else {
			\Log::error("Le dossier 'DOCUMENTS OUVERTURE DE COMPTE' n'existe pas ou n'a pas pu être trouvé.");
			return redirect()->route('compte_client.folder', ['id' => $id])->withErrors(['msg' => "Le dossier 'DOCUMENTS OUVERTURE DE COMPTE' n'existe pas ou n'a pas pu être trouvé."]);

			exit;
		}

		//Vérifier si un dossier pour ce client nommé $id_client existe déjà dans "DOCUMENTS OUVERTURE DE COMPTE" sinon on le créer
		$clientFolderId = null;
		$apiUrl2 = "https://ged.maileva.com/api/folder/find?parent_id=$compteFolderId&name=$clientId";
		$response = self::curlExecute($apiUrl2);

		if ($data !== null && $data['success'] === true) {
			$clientFolderId = $data['data']['id'];
		} else {
			// Si le dossier client n'existe pas, on le crée


			$apiUrl = "https://ged.maileva.com/api/folder/";
			$postData = json_encode(array(
				"name" => $clientId,
				"parentId" => $compteFolderId
			));
			$curl = curl_init();
			curl_setopt_array($curl, array(
				CURLOPT_CUSTOMREQUEST => 'POST',
				CURLOPT_URL => $apiUrl,
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_POSTFIELDS => $postData,
				CURLOPT_HTTPHEADER => $headers,
				CURLOPT_SSL_VERIFYPEER => false
			));
			$response = curl_exec($curl);
			$err = curl_error($curl);
			curl_close($curl);

			if ($err) {

				\Log::error("Erreur cURL lors de la création du dossier client ");
				return redirect()->route('compte_client.folder', ['id' => $id])->withErrors(['msg' => "Erreur cURL lors de la création du dossier client: " . $err]);
				exit;
			} else {
				$data = json_decode($response, true);
				if ($data !== null && $data['success'] === true) {
					$clientFolderId = $data['data']['id'];
				} else {
					\Log::error("Erreur lors de la création du dossier client ");
					//return back()->withErrors(['msg' => "Erreur lors de la création du dossier client"]);
					return redirect()->route('compte_client.folder', ['id' => $id])->withErrors(['msg' => "Erreur lors de la création du dossier client"]);

					exit;
				}
			}
		}

		$typeDoc = '';

		switch ($type) {
			case 1:
				$typeDoc = "DOCUMENTS OUVERTURE DE COMPTE POIDS";
				break;
			case 2:
				$typeDoc = "PRINCIPES ET CODE DES PRATIQUES DU RJC ET DE SAAMP";
				break;
			case 3:
				$typeDoc = "DECLARATION DUE DILIGENCE";
				break;
			case 4:
				$typeDoc = "CNI OU PASSEPORT";
				break;
			case 5:
				$typeDoc = "KBIS DE MOINS DE 3 MOIS OU REPERTOIRE DES METIERS";
				break;
			case 6:
				$typeDoc = "DECLARATION DEXISTENCE AUPRES DE LA GARANTIE";
				break;
			case 7:
				$typeDoc = "LETTRE DE FUSION";
				break;
			case 8:
				$typeDoc = "RIB";
				break;
			case 9:
				$typeDoc = "AEX";
				break;
			case 10:
				$typeDoc = "AUTORISATION DE DECLARATION EN DOUANE";
				break;
			case 11:
				$typeDoc = "QUALITE";
				break;
			case 12:
				$typeDoc = "ENQUETE(COMPLIANCE)";
				break;
			case 13:
				$typeDoc = "CONVENTION OCA";
				break;
			case 14:
				$typeDoc = "DIVERS DOCUMENTS";
				break;
		}

		$subfolderPath = "DOCUMENTS OUVERTURE DE COMPTE/$clientId/$typeDoc"; //
		$apiUrl = "https://ged.maileva.com/api/folder/path";
		$postData = json_encode(array(
			"path" => $subfolderPath,
			"parent_id" => $compteFolderId
		));
		$curl = curl_init();
		curl_setopt_array($curl, array(
			CURLOPT_CUSTOMREQUEST => 'POST',
			CURLOPT_URL => $apiUrl,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_POSTFIELDS => $postData,
			CURLOPT_HTTPHEADER => $headers,
			CURLOPT_SSL_VERIFYPEER => false
		));
		$response = curl_exec($curl);
		$err = curl_error($curl);
		curl_close($curl);

		if ($err) {
			echo "Erreur cURL lors de la création du chemin de dossier : " . $err;
			return redirect()->route('compte_client.folder', ['id' => $id])->withErrors(['msg' => "Erreur cURL lors de la création du chemin de dossier :" . $err]);

			exit;
		} else {
			$data = json_decode($response, true);
			if ($data !== null && $data['success'] === true) {
				$newFolderId = $data['data']['id'];
			} else {
				//echo "Erreur lors de la création du sous-dossier.";
				//dd($clientId);
				\Log::error("Erreur cURL lors de la création du sous-dossier");

				return redirect()->route('compte_client.folder', ['id' => $id])->withErrors(['msg' => "Erreur lors de la création du sous-dossier "]);

				exit;
			}
		}

		// Téléchargement du fichier dans le sous-dossier nouvellement créé
		$subfolderPath = "DOCUMENTS OUVERTURE DE COMPTE/$clientId/$typeDoc";
		foreach ($files as $file) {
			$originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
			$extension = $file->getClientOriginalExtension();
			$date= date('d_m_Y_H_i_s');
			// Append the client name to the original name
			$fileName = $originalName . '_' . $client_name . '_' . $date. '.' . $extension;
			$fileType = $file->getMimeType();
			$filePath = $file->getPathname();

			if ($fileType == 'application/pdf') {
				$curlFile = new \CURLFile($filePath, $fileType, $fileName);
				$postFields = array(
					'file' => $curlFile,
					'path' => $subfolderPath
				);

				$curl = curl_init();
				curl_setopt_array($curl, array(
					CURLOPT_URL => 'https://ged.maileva.com/api/document/',
					CURLOPT_RETURNTRANSFER => true,
					CURLOPT_POST => true,
					CURLOPT_POSTFIELDS => $postFields,
					CURLOPT_HTTPHEADER => array(
						'Auth-Token: ' . self::getToken()
					),
					CURLOPT_SSL_VERIFYPEER => false
				));

				$response = curl_exec($curl);
				$err = curl_error($curl);
				curl_close($curl);

				if ($err) {
					\Log::error("Erreur cURL de téléchargement GED pour le fichier : $fileName");
					return redirect()->route('compte_client.folder', ['id' => $id])->withErrors(['msg' => "Erreur cURL de téléchargement GED pour le fichier $fileName"]);
				}
			}
		}

		return redirect()->route('compte_client.folder', ['id' => $id])->with(['success' => "Fichiers téléchargés avec succès"]);
	}


	public static function deleteFile($itemId)
	{
 		$headers = array(
			'Content-Type: application/json',
			'Auth-Token: ' . self::getToken()
		);

		$apiUrl = "https://ged.maileva.com/api/document/$itemId";

		$curl = curl_init();

		curl_setopt_array($curl, array(
			CURLOPT_CUSTOMREQUEST => 'DELETE',
			CURLOPT_URL => $apiUrl,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_HTTPHEADER => $headers,
			CURLOPT_SSL_VERIFYPEER => false
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		if ($err) {
			echo "Erreur cURL : " . $err;
			return 0;
		} else {
			$data = json_decode($response, true);
			if ($data && $data['success'] === true) {
				\Log::info('Le document a été supprimé avec succès.');
				return 1;

			} else {
				\Log::info('Erreur lors de la suppression du document');
				return 0;
			}
		}
	}

	// Ajout document offres
	public static function OffreDocs($clientId, $offreId, $id)
	{

		// URL de l'API pour récupérer les sous-dossiers
		$apiUrl = "https://ged.maileva.com/api/folder/name/DOCUMENTS%20OFFRES%20DE%20PRIX";

		$response = self::curlExecute($apiUrl);
		$commercialFolderId = null;

		$headers = array(
			'Content-Type: application/json',
			'Auth-Token: ' . self::getToken()
		);
		// Décodage de la réponse JSON
		$data = json_decode($response, true);

		if ($data !== null && $data['success'] === true) {
			$commercialFolderId = $data['data']['id'];
			\Log::info(" OFFRES DE PRIX commercialFolderId:  $commercialFolderId");

		} else {
			\Log::error("Le dossier 'DOCUMENTS OFFRES DE PRIX' n'existe pas ou n'a pas pu être trouvé.");
			return redirect()->route('offres.client_list', ['id' => $id])->withErrors(['msg' => "Le dossier 'DOCUMENTS OFFRES DE PRIX' n'existe pas ou n'a pas pu être trouvé."]);

			exit;
		}

		//Vérifier si un dossier pour ce client nommé $id_client existe déjà dans "DOCUMENTS OUVERTURE DE COMPTE" sinon on le créer
		$clientFolderId = null;
		$apiUrl2 = "https://ged.maileva.com/api/folder/find?parent_id=$commercialFolderId&name=$clientId";
		$response = self::curlExecute($apiUrl2);

		if ($data !== null && $data['success'] === true) {
			$clientFolderId = $data['data']['id'];
		} else {
			// Si le dossier client n'existe pas, on le crée

			\Log::info(" Creation dossier OFFRES DE PRIX  ");

			$apiUrl = "https://ged.maileva.com/api/folder/";
			$postData = json_encode(array(
				"name" => $clientId,
				"parentId" => $commercialFolderId
			));
			$curl = curl_init();
			curl_setopt_array($curl, array(
				CURLOPT_CUSTOMREQUEST => 'POST',
				CURLOPT_URL => $apiUrl,
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_POSTFIELDS => $postData,
				CURLOPT_HTTPHEADER => $headers,
				CURLOPT_SSL_VERIFYPEER => false
			));
			$response = curl_exec($curl);
			$err = curl_error($curl);
			curl_close($curl);

			if ($err) {

				\Log::error("Erreur cURL lors de la création du dossier client ");
				return redirect()->route('compte_client.folder', ['id' => $id])->withErrors(['msg' => "Erreur cURL lors de la création du dossier client: " . $err]);
				exit;
			} else {
				$data = json_decode($response, true);
				if ($data !== null && $data['success'] === true) {
					$clientFolderId = $data['data']['id'];
				} else {
					\Log::error("Erreur lors de la création du dossier client ");
					//return back()->withErrors(['msg' => "Erreur lors de la création du dossier client"]);
					return redirect()->route('offres.client_list.folder', ['id' => $id])->withErrors(['msg' => "Erreur lors de la création du dossier client"]);

					exit;
				}
			}
		}


		$subfolderPath = "DOCUMENTS OUVERTURE DE COMPTE/$clientId/$offreId"; //
		$apiUrl = "https://ged.maileva.com/api/folder/path";
		$postData = json_encode(array(
			"path" => $subfolderPath,
			"parent_id" => $commercialFolderId
		));
		$curl = curl_init();
		curl_setopt_array($curl, array(
			CURLOPT_CUSTOMREQUEST => 'POST',
			CURLOPT_URL => $apiUrl,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_POSTFIELDS => $postData,
			CURLOPT_HTTPHEADER => $headers,
			CURLOPT_SSL_VERIFYPEER => false
		));
		$response = curl_exec($curl);
		$err = curl_error($curl);
		curl_close($curl);

		if ($err) {
			echo "Erreur cURL lors de la création du chemin de dossier : " . $err;
			return redirect()->route('offres.client_list', ['id' => $id])->withErrors(['msg' => "Erreur cURL lors de la création du chemin de dossier :" . $err]);

			exit;
		} else {
			$data = json_decode($response, true);
			if ($data !== null && $data['success'] === true) {
				$newFolderId = $data['data']['id'];
			} else {
				//echo "Erreur lors de la création du sous-dossier.";
				//dd($clientId);
				\Log::error("Erreur cURL lors de la création du sous-dossier");

				return redirect()->route('offres.client_list', ['id' => $id])->withErrors(['msg' => "Erreur lors de la création du sous-dossier "]);

				exit;
			}
		}

		// Téléchargement du fichier dans le sous-dossier nouvellement créé
		$Path = "DOCUMENTS OFFRES DE PRIX/" . $clientId . "/" . $offreId;
		foreach ($_FILES['files']['tmp_name'] as $key => $tmp_name) {
			$fileName = $_FILES['files']['name'][$key];
			$fileType = $_FILES['files']['type'][$key];
			$filePath = $_FILES['files']['tmp_name'][$key];

			$file = new \CURLFile($filePath, $fileType, $fileName);
			$postFields = array(
				'file' => $file,
				'path' => $Path
			);

			$curl = curl_init();
			curl_setopt_array($curl, array(
				CURLOPT_URL => 'https://ged.maileva.com/api/document/',
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_POST => true,
				CURLOPT_POSTFIELDS => $postFields,
				CURLOPT_HTTPHEADER => array(
					'Auth-Token: ' . self::getToken()
				),
				CURLOPT_SSL_VERIFYPEER => false
			));

			$response = curl_exec($curl);
			$err = curl_error($curl);
			curl_close($curl);

			if ($err) {
				//echo "Erreur cURL : " . $err;
				\Log::error("Erreur cURL de telechargement GED" . $err);
				//return back()->withErrors(['msg' => $err]);
				return redirect()->route('offres.client_list', ['id' => $id])->withErrors(['msg' => "Erreur cURL de telechargement GED" . $err]);
			} else {
				//echo "Fichier téléchargé avec succès";
				//return back()->with('success', ' Fichier téléchargé avec succès');
				\Log::info(" telechargement GED offre avec succes" . $Path);

				//return redirect()->route('offres.folder', ['id' => $id])->with(['success' => "Fichier téléchargé avec succès "]);
			}
		}
	}



    public static function deleteFolder($id) {

		$headers = array(
			'Content-Type: application/json',
			'Auth-Token: ' . self::getToken()
		);

		$url = "https://ged.maileva.com/api/folder/$id";

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_CUSTOMREQUEST => 'DELETE',
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_HEADER => true,
            CURLOPT_SSL_VERIFYPEER => false,
        ]);

        $response = curl_exec($curl);

        if ($response === false) {
            $err = curl_error($curl);
            curl_close($curl);
            throw new Exception("Erreur cURL : $err");
        }

        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        // Vérifie le code HTTP
        if ($httpCode >= 200 && $httpCode < 300) {
            // Analyse de la réponse JSON
            //$data = json_decode($response, true);
			\Log::info("Le dossier a été supprimé avec succès.");
			return true;

        } else {
            // Gestion des erreurs
            \Log::info("Erreur lors de la suppression. Code HTTP : $httpCode ");
			return false;
        }
    }



	public static function expireDates($clientId)
	{
		try {

			$server = env('AS400_server');
			$user = env('AS400_user');
			$pass = env('AS400_pass');
			$dsn = "Driver={IBM i Access ODBC Driver};System=$server;Uid=$user;Pwd=$pass";
			//$dsn = "Driver={IBM i Access ODBC Driver};System=82.96.140.216;Uid=BOUREY;Pwd=BOUREY";
			//$dsn = "DRIVER={iSeries Access ODBC Driver};SYSTEM=82.96.140.216;DBNAME=S65DD73D;UID=BOUREY;PWD=BOUREY;charset=utf8";

			$pdo = new \PDO("odbc:$dsn", $user, $pass);
			$pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
		} catch (PDOException $e) {
			dd("Erreur de connexion : " . $e->getMessage());
			exit;
		}

		$expDates = array();
		try {
			// Requête pour recup les dates dans la table GESCOMF.CLIRJCP1
			// Les colonnes DTRJC1 à DTRJC9
			$stmt = $pdo->prepare("SELECT DTRJC1, DTRJC2, DTRJC3, DTRJC4, DTRJC5, DTRJC6, DTRJC7, DTRJC8, DTRJC9 FROM GESCOMF.CLIRJCP1 WHERE NUCLI = :clientId");
			$stmt->execute([':clientId' => $clientId]);
			$docDates = $stmt->fetch(PDO::FETCH_ASSOC);

			// Debug : Affichage du résultat brut de la requête
			// echo "<pre>Résultat brut de la requête : " . print_r($docDates, true) . "</pre>";

			if ($docDates) {
				// Boucle sur les types de documents de 1 à 9
				for ($type = 1; $type <= 9; $type++) {
					$col = "DTRJC" . $type;
					// On ignore les champs vides ou égaux à "0"
					if (!empty($docDates[$col]) && $docDates[$col] != "0") {
						$dateStr = $docDates[$col];
						// Format AS/400 : YYMMDD (exemple : "260706")
						$yearAS400 = substr($dateStr, 0, 2);
						$month = substr($dateStr, 2, 2);
						$day = substr($dateStr, 4, 2);
						$year = 2000 + intval($yearAS400);
						$formatted = "$day/$month/$year";

						// Association du numéro de document au label utilisé pour le dossier
						switch ($type) {
							case 1:
								$label = "DOCUMENTS OUVERTURE DE COMPTE POIDS";
								break;
							case 2:
								$label = "PRINCIPES ET CODE DES PRATIQUES DU RJC ET DE SAAMP";
								break;
							case 3:
								$label = "DECLARATION DUE DILIGENCE";
								break;
							case 4:
								$label = "CNI OU PASSEPORT";
								break;
							case 5:
								$label = "KBIS DE MOINS DE 3 MOIS OU REPERTOIRE DES METIERS";
								break;
							case 6:
								$label = "DECLARATION D'EXISTENCE AUPRES DE LA GARANTIE";
								break;
							case 7:
								$label = "LETTRE DE FUSION";
								break;
							case 8:
								$label = "RIB";
								break;
							case 9:
								$label = "CONVENTION OCA";
								break;
							default:
								$label = "";
						}
						// Debug : Affichage du libellé et de la date formatée pour ce document
						// debug echo "<pre>Document type $type ($label) : Date = $formatted</pre>";

						// Enregistrer la date d'expiration pour ce document
						$expDates[$label] = $formatted;
					} else {
						// debug echo "<pre>Aucune date trouvée pour DTRJC$type</pre>";
					}
				}

				// Debug : Affichage du tableau final des dates d'expiration
				// echo "<pre>Tableau final des dates d'expiration : " . print_r($expDates, true) . "</pre>";
			} else {
				//echo "<pre>Aucune donnée retournée pour ce client.</pre>";
			}
		} catch (\PDOException $e) {
			dd("Erreur lors de la requête AS/400 : " . $e->getMessage());
		}
		return $expDates;
	}


}
