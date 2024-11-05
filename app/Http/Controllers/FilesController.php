<?php
namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\CompteClient;
use App\Models\RetourClient;
use App\Models\Contact;
use App\Models\Offre;
use App\Models\File;

use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Auth;


class FilesController extends Controller
{

	  public function __construct()
    {
        $this->middleware('auth');
    }
/*
    public function destroyFile(File $file)
    {
        // retours
        $filePath = public_path("fichiers/$file->parent/{$file->name}");
        if (file_exists($filePath)) {
            unlink($filePath);  // Delete file from server
        }
        $file->delete();  // Remove record from database

        return back()->with('success', 'File deleted successfully');
    }
*/
    public function destroyFile(File $file)
    {
        $filePath = public_path("/retours/{$file->name}");
        if (file_exists($filePath)) {
            unlink($filePath);  // Delete file from server
        }

        $file->delete();  // Remove record from database

		//return redirect()->back()->with('success', 'Supprimé avec succès');
        return response()->json(['success' => true, 'message' => 'File deleted successfully']);

    }
}
