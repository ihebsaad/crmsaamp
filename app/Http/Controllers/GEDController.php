<?php
namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Auth;

use App\Services\GEDService;

use Illuminate\Support\Facades\Session;

class GEDController extends Controller
{

	  public function __construct()
    {
        $this->middleware('auth');
    }

/*
	public function folders()
	{
		try{
			$clientId=auth()->user()->client_id;
			$files=false;
			$parent=null;
			if (isset($clientId)) {
				$folders=GEDService::getFolderParent($clientId);
			}

		} catch (\Exception $e) {
			\Log::info(' erreur GED '.$e->getMessage());
			return "Erreur : " . $e->getMessage();
		}
		finally {
			\Log::info('GED folders show ' );
		}
		return view('ged.folders',compact('folders','files'));
	}


	public function folderContent($folderId,$folderName,$parent=null)
	{
		try{
			$clientId=auth()->user()->client_id;

			if (isset($clientId)) {
				$folders=GEDService::getFolderList($folderId);
				//$folderContent=GEDService::getFolderContent($folderId);
				$result=GEDService::getFolderContent($folderId);
				$folderContent=$result['data'] ?? [] ;
				$files=false;
				if(!$folders){
					$folders=GEDService::getFolderList($parent);
					$files=true;
					//dd($parent);
				}
			}

		} catch (\Exception $e) {
			\Log::info(' erreur GED '.$e->getMessage());
			return "Erreur : " . $e->getMessage();
		}
		finally {
			\Log::info('GED folder show ' );
		}
		return view('ged.folders',compact('folders','folderName','folderContent','parent','files','folderId'));
	}
*/
	public function download($id)
	{
		try{
			$clientId=auth()->user()->client_id;

			if (isset($clientId)) {
				GEDService::downloadItem($id);
			}

		} catch (\Exception $e) {
			\Log::info(' erreur GED '.$e->getMessage());
			return "Erreur : " . $e->getMessage();
		}
	}

	public function view($id)
	{
		try{
			$clientId=auth()->user()->client_id;

			if (isset($clientId)) {
				$result = GEDService::getItem($id);

				if ($result) {
					return response($result, 200)
						->header('Content-Type', 'application/pdf');
				}
			}

		} catch (\Exception $e) {
			\Log::info(' erreur GED '.$e->getMessage());
			return "Erreur : " . $e->getMessage();
		}

		return "Document not found or access denied.";

	}


	public function delete_folder($id){
		$result= GEDService::deleteFolder($id);
		return $result;
	}


}
