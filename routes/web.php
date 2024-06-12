<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ClientsController;
use App\Http\Controllers\RetoursController;
use App\Http\Controllers\ContactsController;
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


Route::get('/refresh', 'App\Http\Controllers\Auth\LoginController@refresh')->name('refresh');
Route::get('users/loginas/{id}', 'App\Http\Controllers\UsersController@loginas')->name('loginas');

Route::get('/search', [ClientsController::class, 'search'])->name('search');
Route::post('/search', [ClientsController::class, 'search']);
Route::get('/clients/create', [ClientsController::class, 'create'])->name('compte_client.create');
Route::get('/clients/fiche/{id}', [ClientsController::class, 'fiche'])->name('fiche');
Route::get('/clients/finances/{id}', [ClientsController::class, 'finances'])->name('finances');
Route::get('/clients/phone/{id}', [ClientsController::class, 'phone'])->name('phone');
Route::post('/ajoutclient', [ClientsController::class, 'store'])->name('compte_client.store');
#Route::post('/update', [ClientsController::class, 'update'])->name('compte_client.update');
Route::put('/compte_client/{id}', [ClientsController::class, 'update'])->name('compte_client.update');
Route::get('/clients/show/{id}', [ClientsController::class, 'show'])->name('compte_client.show');



Route::get('/retours/show/{id}', [RetoursController::class, 'show'])->name('retours.show');
Route::put('/retours/{id}', [RetoursController::class, 'update'])->name('retours.update');


Route::get('/contacts/show/{id}', [ContactsController::class, 'show'])->name('contacts.show');
Route::put('/contacts/{id}', [ContactsController::class, 'update'])->name('contacts.update');










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



