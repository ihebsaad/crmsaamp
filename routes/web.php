<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ClientsController;
use App\Http\Controllers\RetoursController;
use App\Http\Controllers\ContactsController;
use App\Http\Controllers\GmailController;
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TachesController;
use App\Http\Controllers\OffresController;
use App\Http\Controllers\RendezVousController;
use App\Http\Controllers\StatsController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\FilesController;
use App\Http\Controllers\CommunicationsController;
use App\Http\Controllers\MapController;
use App\Http\Controllers\RecapController;
use App\Http\Controllers\EmailTemplateController;
use App\Http\Controllers\AgendaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CreditSafeController;

use App\Exports\UserLoginsExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

Route::resource('email-templates', EmailTemplateController::class);

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Auth::routes();

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::get('/confid/', 'App\Http\Controllers\HomeController@confid')->name('confid');
Route::get('/regles/', 'App\Http\Controllers\HomeController@regles')->name('regles');

Route::get('/parcours', [MapController::class, 'parcours'])->name('map.parcours');
Route::post('/parcours', [MapController::class, 'parcours']);


Route::post('/add_template', [EmailTemplateController::class, 'store'])->name('templates.store');
Route::post('/ajout_template', [EmailTemplateController::class, 'add'])->name('templates.add');


Route::post('/add_comment',[ClientsController::class, 'add_comment'])->name('add_comment');
Route::post('/delete_comment',[ClientsController::class, 'delete_comment'])->name('delete_comment');



Route::get('/recap', [RecapController::class, 'recap'])->name('recap');

Route::get('/auth/google/redirect', [GoogleController::class, 'redirectToGoogle'])->name('google.auth.redirect');
Route::get('/auth/google/callback', [GoogleController::class, 'handleGoogleCallback'])->name('google.auth.callback');
Route::get('/google/callback', [GoogleController::class, 'handleGoogleCallback']);

Route::get('/configure-webhook', [GoogleController::class, 'subscribeToGoogleCalendar']);
Route::post('/google-calendar/webhook', [GoogleController::class, 'webhook']);


// Affiche la liste des tickets
Route::get('/tickets/list', [TicketController::class, 'index'])->name('tickets.index');
Route::get('/tickets/create', [TicketController::class, 'create'])->name('tickets.create');
Route::post('/tickets/store', [TicketController::class, 'store'])->name('tickets.store');
Route::get('/tickets/{ticket}', [TicketController::class, 'show'])->name('tickets.show');
Route::get('/tickets/{ticket}/edit', [TicketController::class, 'edit'])->name('tickets.edit');
Route::put('/tickets/{ticket}', [TicketController::class, 'update'])->name('tickets.update');
Route::delete('/tickets/{ticket}', [TicketController::class, 'destroy'])->name('tickets.destroy');

##Route::resource('tickets', TicketController::class);
#Route::get('/tickets', [TicketController::class,'index'])->name('tickets.index');
#Route::post('/tickets/store', [TicketController::class,'store'])->name('tickets.store');
Route::post('tickets/{ticket}/comments', [CommentController::class, 'store'])->name('tickets.comments.store');



Route::post('/setlanguage', 'App\Http\Controllers\HomeController@setlanguage')->name('setlanguage');

Route::get('/home', 'App\Http\Controllers\HomeController@index')->name('home')->middleware('verified');
Route::get('/statistiques', 'App\Http\Controllers\HomeController@statistiques')->name('statistiques');
Route::get('/adminhome', 'App\Http\Controllers\DashboardController@adminhome')->name('adminhome');
Route::get('/dashboard', 'App\Http\Controllers\DashboardController@dashboard')->name('dashboard');
Route::get('/supervision', 'App\Http\Controllers\DashboardController@supervision')->name('supervision');
Route::get('/help', 'App\Http\Controllers\HomeController@help')->name('help');
Route::get('/stats_tasks', 'App\Http\Controllers\HomeController@stats_tasks')->name('stats_tasks');
Route::get('/stats_spot/{type}', [DashboardController::class, 'stats_spot'])->name('stats_spot');

Route::get('/terms/check', [DashboardController::class, 'check'])->name('terms.check');
Route::post('/terms/accept', [DashboardController::class, 'accept'])->name('terms.accept');

Route::get('/clients/phone', [HomeController::class, 'phone'])->name('phone');

Route::get('/agenda', [AgendaController::class, 'agenda'])->name('agenda');
#Route::post('/agenda', [HomeController::class, 'agenda'])->name('agenda');
Route::get('/print_agenda', [AgendaController::class, 'print_agenda'])->name('print_agenda');
Route::get('/pdf_agenda', [AgendaController::class, 'pdf_agenda'])->name('pdf_agenda');
Route::get('/pdf-synthese', [AgendaController::class, 'pdf_synthese'])->name('pdf_synthese');
Route::get('/exterieurs', [AgendaController::class, 'rendesvous_ext'])->name('exterieurs');
#Route::post('/exterieurs', [HomeController::class, 'rendesvous_ext'])->name('exterieurs');
/*
Route::get('/export-excel', [AgendaController::class, 'excel_agenda'])->name('excel_agenda');
Route::get('/export-agenda', [AgendaController::class, 'export_agenda'])->name('export_agenda');
*/

Route::get('/refresh', 'App\Http\Controllers\Auth\LoginController@refresh')->name('refresh');
//Route::get('users/loginas/{id}', 'App\Http\Controllers\UsersController@loginas')->name('loginas');
Route::post('users/loginas', 'App\Http\Controllers\UsersController@loginas')->name('loginas');
// Pour revenir à l'utilisateur précédent (GET)
Route::get('users/revert-login/{id}', 'App\Http\Controllers\UsersController@revertLogin')->name('revert.login');

Route::get('/search', [ClientsController::class, 'search'])->name('search');
Route::post('/search', [ClientsController::class, 'search']);
Route::get('/clients/create', [ClientsController::class, 'create'])->name('compte_client.create');
Route::get('/clients/fiche/{id}', [ClientsController::class, 'fiche'])->name('fiche');
Route::get('/clients/finances/{id}', [ClientsController::class, 'finances'])->name('finances');
#Route::get('/clients/phone/{id}', [ClientsController::class, 'phone'])->name('phone');
Route::post('/ajoutclient', [ClientsController::class, 'store'])->name('compte_client.store');
#Route::post('/update', [ClientsController::class, 'update'])->name('compte_client.update');
Route::put('/compte_client/{id}', [ClientsController::class, 'update'])->name('compte_client.update');
Route::put('/update_finances/{id}', [ClientsController::class, 'update_finances'])->name('compte_client.update_finances');
Route::get('/clients/show/{id}', [ClientsController::class, 'show'])->name('compte_client.show');
Route::get('/clients/folder/{id}', [ClientsController::class, 'folder'])->name('compte_client.folder');
Route::get('/prospects', [ClientsController::class, 'prospects'])->name('prospects');
Route::get('/clients/destroy/{id}',[ClientsController::class,'destroy'])->name('clients.destroy');
Route::get('/activites_client', [ClientsController::class, 'activites_client'])->name('activites_client');
Route::post('/ouverture', [ClientsController::class, 'ouverture'])->name('ouverture');



Route::get('/retours/show/{id}', [RetoursController::class, 'show'])->name('retours.show');
Route::put('/retours/{id}', [RetoursController::class, 'update'])->name('retours.update');
Route::post('/ajoutretour', [RetoursController::class, 'store'])->name('retours.store');
Route::get('/retours/create/{id}', [RetoursController::class, 'create'])->name('retours.create');
Route::put('/retours/{id}', [RetoursController::class, 'update'])->name('retours.update');
Route::get('/retours/list', [RetoursController::class, 'index'])->name('retours.list');
Route::get('/retours/destroy/{id}',[RetoursController::class,'destroy'])->name('retours.destroy');


Route::get('/contacts/show/{id}', [ContactsController::class, 'show'])->name('contacts.show');
Route::post('/ajoutcontact', [ContactsController::class, 'store'])->name('contacts.store');
Route::put('/contacts/{id}', [ContactsController::class, 'update'])->name('contacts.update');
Route::get('/contacts/create/{id}', [ContactsController::class, 'create'])->name('contacts.create');
Route::put('/contacts/{id}', [ContactsController::class, 'update'])->name('contacts.update');
Route::get('/contacts/destroy/{id}',[ContactsController::class,'destroy'])->name('contacts.destroy');

Route::get('/taches/show/{id}', [TachesController::class, 'show'])->name('taches.show');
Route::post('/ajouttache', [TachesController::class, 'store'])->name('taches.store');
Route::put('/taches/{id}', [TachesController::class, 'update'])->name('taches.update');
Route::get('/taches/create/{id}', [TachesController::class, 'create'])->name('taches.create');
Route::put('/taches/{id}', [TachesController::class, 'update'])->name('taches.update');
Route::get('/clientlist/{id}', [TachesController::class, 'client_list'])->name('taches.client_list');
Route::get('/mestaches', [TachesController::class, 'mestaches'])->name('mestaches');
Route::get('/taches', [TachesController::class, 'index'])->name('taches.index');
Route::get('/taches/destroy/{id}',[TachesController::class,'destroy'])->name('taches.destroy');

Route::get('/offres/show/{id}', [OffresController::class, 'show'])->name('offres.show');
Route::post('/ajoutoffre', [OffresController::class, 'store'])->name('offres.store');
Route::put('/offres/{id}', [OffresController::class, 'update'])->name('offres.update');
Route::get('/offres/create/{id}', [OffresController::class, 'create'])->name('offres.create');
Route::get('/offres/clientlist/{id}', [OffresController::class, 'client_list'])->name('offres.client_list');
Route::get('/offres/list', [OffresController::class, 'index'])->name('offres.index');
Route::get('/offres/liste', [OffresController::class, 'liste'])->name('offres.liste');
Route::get('/offres/getdata', [OffresController::class, 'getData'])->name('offres.getdata');
Route::get('/offres/edit_file/{item}/{id}/{name}', 'App\Http\Controllers\OffresController@edit_file');
Route::post('/offres/editFile', 'App\Http\Controllers\OffresController@editFile')->name('offres.editFile');
Route::get('/offres/destroy/{id}',[OffresController::class,'destroy'])->name('offres.destroy');

Route::post('/delete_hist',[OffresController::class, 'delete_hist'])->name('delete_hist');
Route::post('/add_hist',[OffresController::class, 'add_hist'])->name('add_hist');

Route::get('/test', 'App\Http\Controllers\OffresController@test');

Route::get('/rendezvous/show/{id}', [RendezVousController::class, 'show'])->name('rendezvous.show');
Route::get('/rendezvous/print/{id}', [RendezVousController::class, 'print'])->name('rendezvous.print');
Route::post('/ajoutrv', [RendezVousController::class, 'store'])->name('rendezvous.store');
Route::put('/rendezvous/{id}', [RendezVousController::class, 'update'])->name('rendezvous.update');
Route::get('/rendezvous/create/{id}', [RendezVousController::class, 'create'])->name('rendezvous.create');
Route::get('/rendezvous', [RendezVousController::class, 'index'])->name('rendezvous.index');
Route::get('/rendezvous/destroy/{id}',[RendezVousController::class,'destroy'])->name('rendezvous.destroy');
Route::post('/rendezvous/{id}/delete-file', [RendezvousController::class, 'deleteFile'])->name('fichier.delete');


##Route::get('auth/google', [GoogleController::class, 'redirectToGoogle'])->name('google.auth');
##Route::get('oauth2/callback', [GoogleController::class, 'handleGoogleCallback']);

// Routes pour consulter la boîte de réception Gmail
Route::middleware(['auth'])->group(function () {
    Route::get('gmail/access', [GmailController::class, 'access'])->name('gmail.access');
    Route::get('gmail/messages', [GmailController::class, 'listMessages'])->name('gmail.messages');
});


Route::get('/profile', 'App\Http\Controllers\UsersController@profile')->name('profile');
Route::get('/adduser', 'App\Http\Controllers\UsersController@adduser')->name('adduser');
Route::get('/users', 'App\Http\Controllers\UsersController@index')->name('users');
Route::get('/view/{id}', 'App\Http\Controllers\UsersController@view')->name('view');
Route::post('/adding','App\Http\Controllers\UsersController@adding')->name('adding');
Route::post('/checkexiste','App\Http\Controllers\HomeController@checkexiste')->name('checkexiste');
Route::post('/send_demand','App\Http\Controllers\HomeController@send_demand')->name('send_demand');
Route::post('/registration','App\Http\Controllers\UsersController@registration')->name('registration');
Route::post('/updatinguser','App\Http\Controllers\UsersController@updatinguser')->name('updatinguser');
Route::post('/update_role','App\Http\Controllers\UsersController@update_role')->name('update_role');
Route::get('/users/destroy/{id}','App\Http\Controllers\UsersController@destroy')->name('users.destroy');
Route::post('/users/updating','App\Http\Controllers\UsersController@updating')->name('users.updating');
Route::post('/users/famille','App\Http\Controllers\UsersController@famille')->name('users.famille');
Route::get('/consultations', 'App\Http\Controllers\UsersController@consultations')->name('consultations');

Route::post('/users/updatealliage','App\Http\Controllers\UsersController@updatealliage')->name('users.updatealliage');
Route::post('/users/updatecurrency','App\Http\Controllers\UsersController@updatecurrency')->name('users.updatecurrency');
Route::post('/users/updateunit','App\Http\Controllers\UsersController@updateunit')->name('users.updateunit');

Route::post('/updateuser','App\Http\Controllers\UsersController@updateuser')->name('updateuser');
Route::post('/updatecomp','App\Http\Controllers\UsersController@updatecomp')->name('updatecomp');
Route::post('/updateclient','App\Http\Controllers\UsersController@updateclient')->name('updateclient');


Route::get('/commandeprod/{id}','App\Http\Controllers\HomeController@commande')->name('commande');

//STATS
Route::get('/stats', [StatsController::class, 'stats'])->name('stats');
Route::get('/clients/stats', [StatsController::class, 'stats_client'])->name('stats_client');
Route::get('/stats_commercial', [StatsController::class, 'stats_commercial'])->name('stats_commercial');
Route::get('/stats_commercial_client', [StatsController::class, 'stats_commercial_client'])->name('stats_commercial_client');
Route::get('/stats_commercial_client_12', [StatsController::class, 'stats_commercial_client_12'])->name('stats_commercial_client_12');
Route::get('/stats_agence', [StatsController::class, 'stats_agence'])->name('stats_agence');
Route::get('/stats_agence_client', [StatsController::class, 'stats_agence_client'])->name('stats_agence_client');
Route::get('/stats_agences', [StatsController::class, 'stats_agences'])->name('stats_agences');
Route::get('/stats_clients_inactifs', [StatsController::class, 'stats_clients_inactifs'])->name('stats_clients_inactifs');
Route::get('/stats_actvivites', [StatsController::class, 'stats_actvivites'])->name('stats_actvivites');
Route::get('/stats_actvivites_semaine', [StatsController::class, 'stats_actvivites_semaine'])->name('stats_actvivites_semaine');
Route::get('/export-stats-excel',  [StatsController::class, 'exportStatsExcel'])->name('export.stats.excel');


Route::get('/folders', 'App\Http\Controllers\ClientsController@folders')->name('folders');
Route::get('/folders/{id}/{name}/{parent}/{client_id}', 'App\Http\Controllers\ClientsController@folderContent')->name('folderContent');
Route::get('/download/{id}', 'App\Http\Controllers\ClientsController@download');
Route::get('/viewpdf/{id}', 'App\Http\Controllers\ClientsController@view')->name('showPdf');
Route::get('/delete_file/{id}', 'App\Http\Controllers\ClientsController@delete_file')->name('delete_file');
Route::get('/delete_folder/{id}', 'App\Http\Controllers\GEDController@delete_folder')->name('delete_folder');
Route::get('/edit_file/{item}/{id}/{name}', 'App\Http\Controllers\ClientsController@edit_file');
Route::post('/editFile', 'App\Http\Controllers\ClientsController@editFile')->name('editFile');
Route::post('/relancer', 'App\Http\Controllers\OffresController@relancer')->name('relancer');

Route::delete('/files/{file}', [FilesController::class, 'destroyFile'])->name('files.destroy');

Route::get('communications/create', [CommunicationsController::class, 'create'])->name('communications.create');
Route::post('communications', [CommunicationsController::class, 'store'])->name('communications.store');
Route::get('/communications', [CommunicationsController::class, 'index'])->name('communications.index');
Route::get('/search-ajax', [CommunicationsController::class, 'searchAjax'])->name('search.ajax');
Route::post('/get_communication', [CommunicationsController::class, 'get_communication'])->name('get_communication');
Route::post('/upload-image', [CommunicationsController::class, 'uploadImage']);

Route::get('/logins', 'App\Http\Controllers\UserLoginController@index')->name('logins');
Route::get('/pages/{id}', 'App\Http\Controllers\UserLoginController@pages')->name('pages');
Route::get('/consultations', 'App\Http\Controllers\UserLoginController@consultations')->name('consultations');
Route::post('/delete-old-consultations', 'App\Http\Controllers\UserLoginController@deleteOldConsultations')->name('deleteOldConsultations');

Route::get('export-user-logins', function (Request $request) {
    $debut = $request->get('debut');
    $fin = $request->get('fin');
    $date = date('d-m-Y H_i');

    return Excel::download(new UserLoginsExport($debut, $fin), 'CRM_Saamp_Access_' . $date . '.xlsx');
})->name('export-user-logins');


/*
use App\Jobs\UpdateSequentialIdsJob;

Route::get('/update-ids', function () {
    UpdateSequentialIdsJob::dispatch();
    return 'Job lancé pour mettre à jour les IDs';
});
*/


Route::get('/creditsafe/company-info/{id}', [CreditSafeController::class, 'getCompanyInfo'])->name('creditsafe.company.info');

// Route pour télécharger le rapport
Route::get('/creditsafe/download-report/{id}', [CreditSafeController::class, 'downloadCompanyReport'])->name('creditsafe.download.report');

// Route pour le rendu de la vue popup
Route::get('/creditsafe/info-popup', [CreditSafeController::class, 'showCompanyInfoPopup'])->name('creditsafe.info.popup');


Route::prefix('export')->group(function () {
    // Export commercial stats
    Route::get('/commercial/client12', [StatsController::class, 'exportStatsExcel'])
        ->name('export.commercial.client12');
        
    Route::get('/commercial/metier', [StatsController::class, 'exportCommercialMetier'])
        ->name('export.commercial.metier');
        
    Route::get('/commercial/client', [StatsController::class, 'exportCommercialClient'])
        ->name('export.commercial.client');
    
    // Export agency stats    
    Route::get('/agence/metier', [StatsController::class, 'exportAgenceMetier'])
        ->name('export.agence.metier');
        
    Route::get('/agence/client', [StatsController::class, 'exportAgenceClient'])
        ->name('export.agence.client');
    
    // Export all agencies stats
    Route::get('/agences', [StatsController::class, 'exportAgences'])
        ->name('export.agences');
    
    // Export inactive clients stats
    Route::get('/clients-inactifs', [StatsController::class, 'exportClientsInactifs'])
        ->name('export.clients.inactifs');

    //dashboard
    Route::get('/stats/metal',[DashboardController::class, 'exportMetalStats'])->name('export.stats.metal');

    Route::get('/transactions',[DashboardController::class, 'exportTransactions'])->name('export.transactions');

    Route::get('/stats_reception',[DashboardController::class, 'stats_reception'])->name('export.stats_reception');
    Route::get('/stats_reception_month',[DashboardController::class, 'stats_reception_month'])->name('export.stats_reception_month');

    Route::post('/prospects/print', [ClientsController::class, 'printSorted'])->name('prospects.print');

});

 