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


Route::get('/home', 'App\Http\Controllers\HomeController@index')->name('home')->middleware('verified');
Route::get('/adminhome', 'App\Http\Controllers\HomeController@adminhome')->name('adminhome');

Route::get('/clients/phone', [HomeController::class, 'phone'])->name('phone');


Route::get('/refresh', 'App\Http\Controllers\Auth\LoginController@refresh')->name('refresh');
Route::get('users/loginas/{id}', 'App\Http\Controllers\UsersController@loginas')->name('loginas');

Route::get('/search', [ClientsController::class, 'search'])->name('search');
Route::post('/search', [ClientsController::class, 'search']);
Route::get('/clients/create', [ClientsController::class, 'create'])->name('compte_client.create');
Route::get('/clients/fiche/{id}', [ClientsController::class, 'fiche'])->name('fiche');
Route::get('/clients/finances/{id}', [ClientsController::class, 'finances'])->name('finances');
#Route::get('/clients/phone/{id}', [ClientsController::class, 'phone'])->name('phone');
Route::post('/ajoutclient', [ClientsController::class, 'store'])->name('compte_client.store');
#Route::post('/update', [ClientsController::class, 'update'])->name('compte_client.update');
Route::put('/compte_client/{id}', [ClientsController::class, 'update'])->name('compte_client.update');
Route::get('/clients/show/{id}', [ClientsController::class, 'show'])->name('compte_client.show');



Route::get('/retours/show/{id}', [RetoursController::class, 'show'])->name('retours.show');
Route::put('/retours/{id}', [RetoursController::class, 'update'])->name('retours.update');
Route::post('/ajoutretour', [RetoursController::class, 'store'])->name('retours.store');
Route::get('/retours/create/{id}', [RetoursController::class, 'create'])->name('retours.create');
Route::put('/retours/{id}', [RetoursController::class, 'update'])->name('retours.update');
Route::get('/retours', [RetoursController::class, 'index'])->name('retours.index');


Route::get('/contacts/show/{id}', [ContactsController::class, 'show'])->name('contacts.show');
Route::post('/ajoutcontact', [ContactsController::class, 'store'])->name('contacts.store');
Route::put('/contacts/{id}', [ContactsController::class, 'update'])->name('contacts.update');
Route::get('/contacts/create/{id}', [ContactsController::class, 'create'])->name('contacts.create');
Route::put('/contacts/{id}', [ContactsController::class, 'update'])->name('contacts.update');

Route::get('/taches/show/{id}', [TachesController::class, 'show'])->name('taches.show');
Route::post('/ajouttache', [TachesController::class, 'store'])->name('taches.store');
Route::put('/taches/{id}', [TachesController::class, 'update'])->name('taches.update');
Route::get('/taches/create/{id}', [TachesController::class, 'create'])->name('taches.create');
Route::put('/taches/{id}', [TachesController::class, 'update'])->name('taches.update');
Route::get('/clientlist/{id}', [TachesController::class, 'client_list'])->name('taches.client_list');
Route::get('/contactlist/{id}', [TachesController::class, 'contact_list'])->name('taches.contact_list');
Route::get('/mestaches', [TachesController::class, 'mestaches'])->name('mestaches');
Route::get('/taches', [TachesController::class, 'index'])->name('taches.index');

Route::get('/offres/show/{id}', [OffresController::class, 'show'])->name('offres.show');
Route::post('/ajoutoffre', [OffresController::class, 'store'])->name('offres.store');
Route::put('/offres/{id}', [OffresController::class, 'update'])->name('offres.update');
Route::get('/offres/create/{id}', [OffresController::class, 'create'])->name('offres.create');
Route::get('/offres/clientlist/{id}', [OffresController::class, 'client_list'])->name('offres.client_list');
Route::get('/offres', [OffresController::class, 'index'])->name('offres.index');

Route::get('/rendezvous/show/{id}', [RendezVousController::class, 'show'])->name('rendezvous.show');
Route::post('/ajoutrv', [RendezVousController::class, 'store'])->name('rendezvous.store');
Route::put('/rendezvous/{id}', [RendezVousController::class, 'update'])->name('rendezvous.update');
Route::get('/rendezvous/create/{id}', [RendezVousController::class, 'create'])->name('rendezvous.create');
Route::get('/rendezvous', [RendezVousController::class, 'index'])->name('rendezvous.index');


Route::get('auth/google', [GoogleController::class, 'redirectToGoogle'])->name('google.auth');
Route::get('oauth2/callback', [GoogleController::class, 'handleGoogleCallback']);

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
Route::post('/updatingusertype','App\Http\Controllers\UsersController@updatingusertype')->name('updatingusertype');
Route::get('/users/destroy/{id}','App\Http\Controllers\UsersController@destroy')->name('users.destroy');
Route::post('/users/updating','App\Http\Controllers\UsersController@updating')->name('users.updating');
Route::post('/users/famille','App\Http\Controllers\UsersController@famille')->name('users.famille');

Route::post('/users/updatealliage','App\Http\Controllers\UsersController@updatealliage')->name('users.updatealliage');
Route::post('/users/updatecurrency','App\Http\Controllers\UsersController@updatecurrency')->name('users.updatecurrency');
Route::post('/users/updateunit','App\Http\Controllers\UsersController@updateunit')->name('users.updateunit');

Route::post('/updateuser','App\Http\Controllers\UsersController@updateuser')->name('updateuser');
Route::post('/updatecomp','App\Http\Controllers\UsersController@updatecomp')->name('updatecomp');
Route::post('/updateclient','App\Http\Controllers\UsersController@updateclient')->name('updateclient');


Route::get('/commandeprod/{id}','App\Http\Controllers\HomeController@commande')->name('commande');


/*
use App\Jobs\UpdateSequentialIdsJob;

Route::get('/update-ids', function () {
    UpdateSequentialIdsJob::dispatch();
    return 'Job lancé pour mettre à jour les IDs';
});
*/