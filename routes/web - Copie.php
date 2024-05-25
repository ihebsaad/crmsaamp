<?php

use Illuminate\Support\Facades\Route;

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
//use Spatie\Honeypot\ProtectAgainstSpam;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

/*
Route::get('email/verify', 'App\Http\Controllers\Auth\VerificationController@show')->name('verification.notice');
Route::get('email/verify/{id}', 'App\Http\Controllers\Auth\VerificationController@verify')->name('verification.verify');
Route::get('email/resend', 'App\Http\Controllers\Auth\VerificationController@resend')->name('verification.resend');
*/
//Route::group([], __DIR__ . '/import.php');


Route::get('/home', 'App\Http\Controllers\HomeController@index')->name('home')->middleware('verified');
Route::get('/adminhome', 'App\Http\Controllers\HomeController@adminhome')->name('adminhome');
Route::get('/visitor', 'App\Http\Controllers\UsersController@visitor')->name('visitor');


//Route::get('/', 'App\Http\Controllers\PagesController@welcome')->name('welcome');
//Route::get('/verify', 'App\Http\Controllers\UsersController@verify')->name('verify');

Route::get('/refresh', 'App\Http\Controllers\Auth\LoginController@refresh')->name('refresh');
Route::get('users/loginas/{id}', 'App\Http\Controllers\UsersController@loginas')->name('loginas');

Route::post('/data', 'App\Http\Controllers\ProductsController@data')->name('data');
Route::get('/single/{type}/{fam1}/{fam2}/{fam3}', 'App\Http\Controllers\ProductsController@single')->name('single');
//Route::get('/single/{type}/{famille1}/{famille2}', 'App\Http\Controllers\ProductsController@single')->name('single');
Route::post('/modelabel', 'App\Http\Controllers\ProductsController@modelabel')->name('modelabel');
Route::post('/addproduct', 'App\Http\Controllers\ProductsController@addproduct')->name('addproduct');
Route::get('/deleteproduct/{id}', 'App\Http\Controllers\ProductsController@deleteproduct')->name('deleteproduct');
Route::get('/deletemodel/{id}', 'App\Http\Controllers\ProductsController@deletemodel')->name('deletemodel');
Route::get('/deletemodellab/{id}', 'App\Http\Controllers\ProductsController@deletemodellab')->name('deletemodellab');
Route::get('/deletemodelrmp/{id}', 'App\Http\Controllers\ProductsController@deletemodelrmp')->name('deletemodelrmp');
Route::get('/suppmodel/{id}', 'App\Http\Controllers\ProductsController@suppmodel')->name('suppmodel');
Route::get('/suppmodellab/{id}', 'App\Http\Controllers\ProductsController@suppmodellab')->name('suppmodellab');
Route::get('/suppmodelrmp/{id}', 'App\Http\Controllers\ProductsController@suppmodelrmp')->name('suppmodelrmp');
Route::post('/details', 'App\Http\Controllers\ProductsController@details')->name('details');
Route::post('/checkproduct', 'App\Http\Controllers\ProductsController@checkproduct')->name('checkproduct');
Route::post('/forfait', 'App\Http\Controllers\ProductsController@forfait')->name('forfait');
Route::post('/tarifcmd', 'App\Http\Controllers\ProductsController@tarifcmd')->name('tarifcmd');
Route::post('/tariflabo', 'App\Http\Controllers\ProductsController@tariflabo')->name('tariflabo');
Route::post('/tarifrmp', 'App\Http\Controllers\ProductsController@tarifrmp')->name('tarifrmp');
Route::post('/updatecart', 'App\Http\Controllers\ProductsController@updatecart')->name('updatecart');
Route::post('/updateben', 'App\Http\Controllers\ProductsController@updateben')->name('updateben');


Route::get('/orders', 'App\Http\Controllers\PagesController@orders')->name('orders');
//Route::get('/test', 'App\Http\Controllers\PagesController@test')->name('test');
Route::get('/findings', 'App\Http\Controllers\PagesController@findings')->name('findings');
Route::get('/products', 'App\Http\Controllers\PagesController@products')->name('products');
Route::get('/jewelry', 'App\Http\Controllers\PagesController@jewelry')->name('jewelry');
Route::get('/galvano', 'App\Http\Controllers\PagesController@galvano')->name('galvano');
Route::get('/refining', 'App\Http\Controllers\PagesController@refining')->name('refining');
Route::get('/invest', 'App\Http\Controllers\PagesController@invest')->name('invest');
Route::get('/laboratory', 'App\Http\Controllers\PagesController@laboratory')->name('laboratory');
Route::get('/catalog/{type}/{famille1}/{famille2}', 'App\Http\Controllers\PagesController@catalog')->name('catalog');
Route::get('order/{id}', 'App\Http\Controllers\ModelesController@order')->name('order');
Route::get('model/{id}', 'App\Http\Controllers\ModelesController@model')->name('model');
Route::get('/listorders', 'App\Http\Controllers\PagesController@listorders')->name('listorders');
Route::get('/listmodels', 'App\Http\Controllers\PagesController@listmodels')->name('listmodels');


Route::get('/panier', 'App\Http\Controllers\PagesController@panier')->name('panier');
Route::get('/livraison', 'App\Http\Controllers\PagesController@livraison')->name('livraison');
Route::get('/livraisonmod', 'App\Http\Controllers\PagesController@livraisonmod')->name('livraisonmod');
Route::get('/virement', 'App\Http\Controllers\PagesController@virement')->name('virement');
Route::get('/ajout', 'App\Http\Controllers\PagesController@ajout')->name('ajout');
Route::get('/beneficiaires', 'App\Http\Controllers\PagesController@beneficiaires')->name('beneficiaires');
Route::get('/beneficiaire/{id}', 'App\Http\Controllers\PagesController@beneficiaire')->name('beneficiaire');
Route::post('/updatebenif', 'App\Http\Controllers\ProductsController@updatebenif')->name('updatebenif');
Route::get('/orders', 'App\Http\Controllers\PagesController@orders')->name('orders');
Route::get('/euros', 'App\Http\Controllers\PagesController@euros')->name('euros');
Route::get('/poids', 'App\Http\Controllers\PagesController@poids')->name('poids');
Route::get('/spot', 'App\Http\Controllers\PagesController@spot')->name('spot');


Route::get('/affinage', 'App\Http\Controllers\PagesController@affinage')->name('affinage');
Route::get('/modele', 'App\Http\Controllers\PagesController@modele')->name('modele');
Route::get('/viewmodele/{id}', 'App\Http\Controllers\PagesController@viewmodele')->name('viewmodele');
Route::get('/commande/{id}', 'App\Http\Controllers\PagesController@commande')->name('commande');
Route::post('/addmodele', 'App\Http\Controllers\ModelesController@addmodele')->name('addmodele');
Route::post('/updatemodele', 'App\Http\Controllers\ModelesController@updatemodele')->name('updatemodele');
Route::post('/validatemodels', 'App\Http\Controllers\ModelesController@validatemodels')->name('validatemodels');
Route::post('/validatemodelsliv', 'App\Http\Controllers\ModelesController@validatemodelsliv')->name('validatemodelsliv');
Route::post('/validateproducts', 'App\Http\Controllers\ModelesController@validateproducts')->name('validateproducts');


Route::get('/laboratoire', 'App\Http\Controllers\PagesController@laboratoire')->name('laboratoire');
Route::get('/viewmodelelab/{id}', 'App\Http\Controllers\PagesController@viewmodelelab')->name('viewmodelelab');
Route::get('/modelelab', 'App\Http\Controllers\PagesController@modelelab')->name('modelelab');
Route::get('/commandelab/{id}', 'App\Http\Controllers\PagesController@commandelab')->name('commandelab');
Route::post('/addmodelelab', 'App\Http\Controllers\ModelesController@addmodelelab')->name('addmodelelab');
Route::post('/updatemodelelab', 'App\Http\Controllers\ModelesController@updatemodelelab')->name('updatemodelelab');


Route::get('/rachat', 'App\Http\Controllers\PagesController@rachat')->name('rachat');
Route::get('/modelermp', 'App\Http\Controllers\PagesController@modelermp')->name('modelermp');
Route::post('/addmodelermp', 'App\Http\Controllers\ModelesController@addmodelermp')->name('addmodelermp');
Route::post('/updatemodelermp', 'App\Http\Controllers\ModelesController@updatemodelermp')->name('updatemodelermp');
Route::get('/commandermp/{id}', 'App\Http\Controllers\PagesController@commandermp')->name('commandermp');
Route::get('/viewmodelermp/{id}', 'App\Http\Controllers\PagesController@viewmodelermp')->name('viewmodelermp');

Route::get('/commandeprod/{id}', 'App\Http\Controllers\PagesController@commandeprod')->name('commandeprod');

Route::post('/ajoutvirement', 'App\Http\Controllers\ProductsController@ajoutvirement')->name('ajoutvirement');
Route::get('/verifvirement', 'App\Http\Controllers\ProductsController@verifvirement')->name('verifvirement');
Route::post('/ajoutbenefic', 'App\Http\Controllers\ProductsController@ajoutbenefic')->name('ajoutbenefic');



Route::post('/agence', 'App\Http\Controllers\HomeController@agence')->name('agence');

Route::post('/order/updating','App\Http\Controllers\ProductsController@updating')->name('orders.updating');

Route::get('/tests', 'App\Http\Controllers\PagesController@tests');
Route::get('/tests/{type_id}/{fam1_id}/{fam2_id}/{metal}', 'App\Http\Controllers\PagesController@tests')->name('tests');
Route::get('/tarif/{type_id}/{fam1_id}/{fam2_id}/{metal}', 'App\Http\Controllers\PagesController@tarif')->name('tarifs');
Route::get('/tarification', 'App\Http\Controllers\PagesController@tarification')->name('tarification');
Route::get('/tarification/{type_id}', 'App\Http\Controllers\PagesController@tarification');
Route::get('/tarification/{type_id}/{famille1}', 'App\Http\Controllers\PagesController@tarification');

/*

//Route::middleware(ProtectAgainstSpam::class)->group(function() {

// Authentication Routes...
$this->get('login', 'App\Http\Controllers\Auth\LoginController@showLoginForm')->name('login');
$this->post('login', 'App\Http\Controllers\Auth\LoginController@login');
$this->post('logout', 'App\Http\Controllers\Auth\LoginController@logout')->name('logout');
$this->get('logout', 'App\Http\Controllers\Auth\LoginController@logout')->name('logout');

// Registration Routes...
$this->get('register', 'App\Http\Controllers\Auth\RegisterController@showRegistrationForm')->name('register');
$this->post('register', 'App\Http\Controllers\Auth\RegisterController@register');

// Password Reset Routes...
$this->get('password/request', 'App\Http\Controllers\Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
$this->post('password/email', 'App\Http\Controllers\Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');

$this->get('password/reset/{token}', 'App\Http\Controllers\Auth\ResetPasswordController@showResetForm')->name('password.reset');;
$this->post('password/reset', 'App\Http\Controllers\Auth\ResetPasswordController@reset');
$this->post('password/reset/{token}', 'App\Http\Controllers\Auth\ResetPasswordController@reset');

*/

	Auth::routes();
//});

//Auth::routes();

Route::post('/setlanguage', 'App\Http\Controllers\HomeController@setlanguage')->name('setlanguage');


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

//beneficiaires
Route::get('/beneficiaires', 'App\Http\Controllers\UsersController@beneficiaires')->name('beneficiaires');
Route::get('/beneficiairesvalides', 'App\Http\Controllers\UsersController@beneficiairesvalides')->name('beneficiairesvalides');
Route::get('/validatebenef/{id}', 'App\Http\Controllers\UsersController@validatebenef')->name('validatebenef');



Route::post('/listetrading','App\Http\Controllers\TradingController@listetrading')->name('listetrading');





Route::post('/commande', 'App\Http\Controllers\HomeController@commande');



Route::get('/filtres/{code}', 'App\Http\Controllers\HomeController@filtres')->name('filtres');
Route::get('/filtres2/{code}', 'App\Http\Controllers\HomeController@filtres2')->name('filtres2');
Route::get('/catalogue/{code}', 'App\Http\Controllers\HomeController@catalogue')->name('catalogue');
Route::get('/metaldemiproduit/{type}/{fam}', 'App\Http\Controllers\HomeController@metal_demi_produit')->name('metaldemiproduit');
Route::get('/tarifarticle', 'App\Http\Controllers\HomeController@tarif_article')->name('tarifarticle');

Route::get('/referentiels/{lg}', 'App\Http\Controllers\HomeController@referentiels')->name('referentiels');
Route::get('/referentiel', 'App\Http\Controllers\HomeController@referentiel')->name('referentiel');
Route::get('/referentiel1', 'App\Http\Controllers\HomeController@referentiel1')->name('referentiel1');
Route::get('/referentiel2', 'App\Http\Controllers\HomeController@referentiel2')->name('referentiel2');
Route::get('/referentiel3', 'App\Http\Controllers\HomeController@referentiel3')->name('referentiel3');
Route::get('/referentielmetal', 'App\Http\Controllers\HomeController@referentielmetal')->name('referentielmetal');
Route::get('/referentieltitre', 'App\Http\Controllers\HomeController@referentieltitre')->name('referentieltitre');
Route::get('/referentielcouleur', 'App\Http\Controllers\HomeController@referentielcouleur')->name('referentielcouleur');
Route::get('/referentielalliage', 'App\Http\Controllers\HomeController@referentielalliage')->name('referentielalliage');
Route::get('/referentielphoto', 'App\Http\Controllers\HomeController@referentielphoto')->name('referentielphoto');
Route::get('/referentielunite', 'App\Http\Controllers\HomeController@referentielunite')->name('referentielunite');
Route::get('/referentieletat', 'App\Http\Controllers\HomeController@referentieletat')->name('referentieletat');
Route::get('/referentielcomplement', 'App\Http\Controllers\HomeController@referentielcomplement')->name('referentielcomplement');
Route::get('/referentielmodefacturation', 'App\Http\Controllers\HomeController@referentielmodefacturation')->name('referentielmodefacturation');
Route::get('/referentielmodefacturation', 'App\Http\Controllers\HomeController@referentielmodefacturation')->name('referentielmodefacturation');
Route::get('/produit/{type_id}/{fam1}/{fam2}/{fam3}/{id_cl}/{lg}', 'App\Http\Controllers\HomeController@produit')->name('produit');
Route::get('/produitcomplement/{typeid}/{fam1}/{fam2}/{fam3}', 'App\Http\Controllers\HomeController@produitcomplement')->name('produitcomplement');
Route::get('/produitmesure1/{typeid}/{fam1}/{fam2}/{fam3}', 'App\Http\Controllers\HomeController@produitmesure1')->name('produitmesure1');
Route::get('/produitmesure2/{typeid}/{fam1}/{fam2}/{fam3}/{mes1}', 'App\Http\Controllers\HomeController@produitmesure2')->name('produitmesure2');
Route::get('/produitpoids/{typeid}/{fam1}/{fam2}/{fam3}/{mes1}/{mes2}/{all}', 'App\Http\Controllers\HomeController@produitpoids')->name('produitpoids');
Route::get('/detailsproduit/{typeid}/{fam1}/{fam2}/{fam3}/{mes1}/{mes2}/{all}/{qte}/{id_comp}/{val_comp}/{id_cl}/{lg}', 'App\Http\Controllers\HomeController@detailsproduit')->name('detailsproduit');
Route::get('/tarif/{id_comp}/{val_comp}/{id_cl}', 'App\Http\Controllers\HomeController@tarif')->name('tarif');
Route::get('/compprix/{id_comp}/{qte}/{poids}/{id_cl}/{val_comp}', 'App\Http\Controllers\HomeController@compprix')->name('compprix');
Route::get('/prix/{type_id}/{article_id}/{alliage_id}/{qte}/{poids}/{id_cl}/{lg}', 'App\Http\Controllers\HomeController@prix')->name('prix');

Route::get('/clients', 'App\Http\Controllers\HomeController@clients')->name('clients');
Route::get('/checkclient/{siren}/{lg}', 'App\Http\Controllers\HomeController@checkclient')->name('checkclient');
Route::get('/agences/{code_pays}/{id_cl}/{lg}', 'App\Http\Controllers\HomeController@agences')->name('agences');
Route::get('/listeclients/{contact_id}', 'App\Http\Controllers\HomeController@listeclients')->name('listeclients');
Route::get('/adresse/{id_cl}', 'App\Http\Controllers\HomeController@adresse')->name('adresse');
Route::get('/detailsclient/{id_cl}/{lg}', 'App\Http\Controllers\HomeController@detailsclient')->name('detailsclient');



Route::get('/requete1/{code}', 'App\Http\Controllers\HomeController@requete1')->name('requete1');
Route::get('/requete2', 'App\Http\Controllers\HomeController@requete2')->name('requete2');

Route::post('/entetecommande', 'App\Http\Controllers\ModelesController@entetecommande')->name('entetecommande');
Route::post('/lignecommande', 'App\Http\Controllers\ModelesController@lignecommande')->name('lignecommande');

Route::get('/listecommandes/{id_cl}/{lg}', 'App\Http\Controllers\HomeController@listecommandes')->name('listecommandes');
Route::get('/listemodeles/{id_cl}/{lg}', 'App\Http\Controllers\HomeController@listemodeles')->name('listemodeles');
Route::get('/detailscommande/{id_cmd}/{id_cl}/{lg}', 'App\Http\Controllers\HomeController@detailscommande')->name('detailscommande');
Route::get('/tarifdetails/{nature_id}/{titre_or}/{titre_argent}/{titre_platine}/{titre_palladium}/{poids}/{poids_cendres}/{id_cl}/{lg}', 'App\Http\Controllers\HomeController@tarifdetails')->name('tarifdetails');
Route::get('/tarifforfait/{nature_id}/{titre_or}/{titre_argent}/{titre_platine}/{titre_palladium}/{poids}/{id_cl}/{lg}', 'App\Http\Controllers\HomeController@tarifforfait')->name('tarifforfait');

Route::get('/listeprestations/{id_cl}/{lg}', 'App\Http\Controllers\ModelesController@listeprestations')->name('listeprestations');
Route::get('/listecommandeslabo/{id_cl}/{lg}', 'App\Http\Controllers\ModelesController@listecommandeslabo')->name('listecommandeslabo');
Route::get('/listemodeleslabo/{id_cl}/{lg}', 'App\Http\Controllers\ModelesController@listemodeleslabo')->name('listemodeleslabo');
Route::get('/detailscommandelabo/{id_cmd}/{id_cl}/{lg}', 'App\Http\Controllers\ModelesController@detailscommandelabo')->name('detailscommandelabo');


Route::get('/listecommandesrmp/{id_cl}/{lg}', 'App\Http\Controllers\ModelesController@listecommandesrmp')->name('listecommandesrmp');
Route::get('/listemodelesrmp/{id_cl}/{lg}', 'App\Http\Controllers\ModelesController@listemodelesrmp')->name('listemodelesrmp');
Route::get('/detailscommandermp/{id_cmd}/{id_cl}/{lg}', 'App\Http\Controllers\ModelesController@detailscommandermp')->name('detailscommandermp');


/*

});

*/

//Auth::routes();

//Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

//Trading
Route::get('/trading', 'App\Http\Controllers\TradingController@trading')->name('trading');
Route::post('/operation_fixing', 'App\Http\Controllers\TradingController@operation_fixing')->name('operation_fixing');
Route::post('/operation_spot', 'App\Http\Controllers\TradingController@operation_spot')->name('operation_spot');
Route::post('/operation_ordre', 'App\Http\Controllers\TradingController@operation_ordre')->name('operation_ordre');
Route::post('/prepare_spot', 'App\Http\Controllers\TradingController@prepare_spot')->name('prepare_spot');
Route::get('/solde', 'App\Http\Controllers\TradingController@solde')->name('solde');
Route::post('/verify_code', 'App\Http\Controllers\TradingController@verify_code')->name('verify_code');
Route::post('/check_trading', 'App\Http\Controllers\TradingController@check_trading')->name('check_trading');
Route::post('/resend_code', 'App\Http\Controllers\TradingController@resend_code')->name('resend_code');
Route::post('/prepare_invest', 'App\Http\Controllers\TradingController@prepare_invest')->name('prepare_invest');
Route::post('/check_invest', 'App\Http\Controllers\TradingController@check_invest')->name('check_invest');


//Analytics
Route::get('/analytics', 'App\Http\Controllers\AnalyticsController@index')->name('analytics');



Route::get('/test', 'App\Http\Controllers\TestController@test')->name('test');
Route::post('/send_sms', 'App\Http\Controllers\TestController@send_sms')->name('send_sms');
