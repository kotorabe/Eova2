<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\UserController;
use App\Http\Controllers\Auth\UtilisateurController;
use App\Http\Controllers\BackOffice\CategorieController;
use App\Http\Controllers\BackOffice\DevisBController;
use App\Http\Controllers\BackOffice\EquipeController;
use App\Http\Controllers\BackOffice\LivraisonController;
use App\Http\Controllers\LivraisonController as LivrController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DevisController;
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
//Admin
Route::get('/admin123456', function () {
    return view('auth.login2');
});
Route::get('/register123456', function () {
    return view('auth.register');
});

//Authentification
Route::post('/login', [UserController::class,'login'])->name('admin.login');
Route::post('/register', [UserController::class,'register'])->name('admin.register');
Route::post('/logout', [UserController::class,'logout'])->name('admin.logout');

//utilisateur
Route::get('/', function () {
    return view('front.auth.login');
});
Route::get('/regiter_user', function () {
    return view('front.auth.register');
})->name('user.register');

//Route::post('/register_utilisateur', [UtilisateurController::class,'register'])->name('utilisateur.register');

//Auth::routes();

//Route pour les Utilisateurs
//Authentification
Route::group(['prefix' => 'utilisateur'], function () {
    Route::post('/register', [UtilisateurController::class,'register'])->name('utilisateur.register');
    Route::any('/login', [UtilisateurController::class,'login'])->name('utilisateur.login');
    Route::post('/logout', [UtilisateurController::class,'logout'])->name('utilisateur.logout');
    // Ajoutez d'autres routes d'authentification des employés au besoin
    Route::get('/landing', [UtilisateurController::class,'landing'])->name('utilisateur.landing');
});

//Devis Utilisateur
Route::group(['prefix' => 'devis'], function () {
    //insertion devis
    Route::get('/addresse', [DevisController::class,'new_addresse'])->name('devis.addresse')->middleware('auth:utilisateur');
    Route::post('/redirection', [DevisController::class,'redirection'])->name('devis.redirection')->middleware('auth:utilisateur');
    Route::post('/envoie_devis', [DevisController::class,'CreateDevis'])->name('devis.envoie_devis')->middleware('auth:utilisateur');
    //attente
    Route::get('/attente', [DevisController::class,'devisAttente'])->name('devis.attente')->middleware('auth:utilisateur');
    Route::get('/listeObjetAttente/{id}', [DevisController::class,'getAllObjetAttente'])->name('devis.listeObjetAttente')->middleware('auth:utilisateur');
    Route::get('/ObjetAttente/{id}', [DevisController::class,'getObjetAttente'])->name('devis.ObjetAttente')->middleware('auth:utilisateur');
    Route::post('/modifObjet', [DevisController::class,'UpdateObjet'])->name('devis.modification')->middleware('auth:utilisateur');
    Route::get('/RedaddObjet/{id}', [DevisController::class,'ToaddObjet'])->name('devis.Toaddobjet')->middleware('auth:utilisateur');
    Route::post('/ajoutObjet', [DevisController::class,'addObjet'])->name('devis.ajoutObjet')->middleware('auth:utilisateur');
    Route::get('/dltObjet/{id}', [DevisController::class,'deleteObjet'])->name('devis.dltObjet')->middleware('auth:utilisateur');
    //repondu
    Route::get('/repondu', [DevisController::class,'devisRepondu'])->name('devis.repondu')->middleware('auth:utilisateur');
    Route::get('/recu/{id}', [DevisController::class,'getDevisRecu'])->name('devis.recu')->middleware('auth:utilisateur');
    Route::get('/accepte/{id}', [DevisController::class,'accepteDevis'])->name('devis.accepte')->middleware('auth:utilisateur');
    Route::get('/accepteDevisRefuser/{id}', [DevisController::class,'accepteDevisRefuser'])->name('devis.accepteRefuser')->middleware('auth:utilisateur');

    //Accepter
    Route::get('/DevisAccepter', [DevisController::class,'devisAccepter'])->name('devis.devisAccepter')->middleware('auth:utilisateur');
    Route::get('/DevisObjetAccepter/{id}', [DevisController::class,'getObjetDevisAccepter'])->name('devis.devisObjetAccepter')->middleware('auth:utilisateur');

    //reduction
    Route::get('/reduction/{id}', [DevisController::class,'reductionDevis'])->name('devis.reduction')->middleware('auth:utilisateur');
    Route::get('/getAllObjetReduction/{id_devis}', [DevisController::class,'getAllObjetAttenteReduction'])->name('devis.allObjetReduction')->middleware('auth:utilisateur');

    //refuser
    Route::get('/refus/{id}', [DevisController::class,'refuserDevis'])->name('devis.refus')->middleware('auth:utilisateur');
    Route::get('/getAllObjetRefuser/{id_devis}', [DevisController::class,'getAllObjetAttenteRefuser'])->name('devis.allObjetRefuser')->middleware('auth:utilisateur');

    //Suppression devis
    Route::get('/supprDevis/{id_devis}', [DevisController::class,'supprimerDevis'])->name('devis.deleteDevis')->middleware('auth:utilisateur');

    //Accepter date dispo
    Route::any('/accepterDate', [DevisController::class,'acceptDateDispo'])->name('devis.acceptDateDispo')->middleware('auth:utilisateur');

    //Refuser date dispo
    Route::any('/refuserDate', [DevisController::class,'RefusDateDispo'])->name('devis.RefusDateDispo')->middleware('auth:utilisateur');

});

Route::group(['prefix' => 'livraison'], function () {
    Route::get('/liste', [LivrController::class,'index'])->name('livr.liste')->middleware('auth:utilisateur');
});

Route::group(['prefix' => 'admin123456'], function () {
    Route::get('/home', 'DashboardController@index')->name('dashboard')->middleware('auth');
});


//Dashboard
Route::group(['prefix' => 'admin123456/dashboard'], function () {
    Route::post('/purgeDevis', [DashboardController::class,'purgeDevis'])->name('dashboard.purgeDevis')->middleware('auth');
});

//Equipes
Route::group(['prefix' => 'admin123456/equipe'], function () {
    Route::get('/redirection', [EquipeController::class,'redirectionEquipe'])->name('equipe.redirection')->middleware('auth');
    Route::post('/addEquipe', [EquipeController::class,'storeEquipe'])->name('equipe.addEquipe')->middleware('auth');
    Route::get('/allEquipe', [EquipeController::class,'getAllEquipe'])->name('equipe.allEquipe')->middleware('auth');
    Route::get('/getEquipeCategorie', [EquipeController::class,'getEquipeCategorie'])->name('equipe.getEquipeCategorie')->middleware('auth');
    Route::get('/getEquipe/{id}', [EquipeController::class,'getEquipe'])->name('equipe.getEquipe')->middleware('auth');
    Route::any('/modifEquipe', [EquipeController::class,'updtEquipe'])->name('equipe.updtEquipe')->middleware('auth');
    Route::any('/dltEquipe', [EquipeController::class,'deleteEquipe'])->name('equipe.dltEquipe')->middleware('auth');
});


//Categorie
Route::group(['prefix' => 'admin123456/categorie'], function () {
    Route::get('/redirection', [CategorieController::class,'redirectionCategorie'])->name('categorie.redirection')->middleware('auth');
    Route::post('/addCategorie', [CategorieController::class,'storeCategorie'])->name('categorie.addCategorie')->middleware('auth');
    Route::get('/getcategorie/{id}', [CategorieController::class,'getCategorie'])->name('categorie.getCategorie')->middleware('auth');
    Route::any('/modifCategorie', [CategorieController::class,'updtCategorie'])->name('categorie.updtCategorie')->middleware('auth');
    Route::any('/dltCategorie{id}', [CategorieController::class,'deleteCategorie'])->name('categorie.dltCategorie')->middleware('auth');
});



//Devis Admin
Route::group(['prefix' => 'devis123456'], function () {
    Route::get('/attente', [DevisBController::class,'devisAttente'])->name('devisb.attente')->middleware('auth');
    Route::get('/listeObjetAttente/{id}/{id_utilisateur}', [DevisBController::class,'getAllObjetAttente'])->name('devisb.listeObjetAttente')->middleware('auth');
    Route::get('/listeObjetEnvoyer/{id}/{id_utilisateur}', [DevisBController::class,'getAllObjetEnvoyer'])->name('devisb.listeObjetEnvoyer')->middleware('auth');
    Route::get('/getObjetAttente/{id}', [DevisBController::class,'getObjetAttente'])->name('devisb.getObjetAttente')->middleware('auth');
    Route::any('/storePriceObjet', [DevisBController::class,'addPriceToObjet'])->name('devisb.addPriceToObjet')->middleware('auth');
    //envoie devis à l'utilisateur
    Route::get('/sendDevisToUser/{id_devis}/{id_utilisateur}', [DevisBController::class,'sendDevis'])->name('devisb.sendDevis')->middleware('auth');
    //repondu
    Route::get('/repondu', [DevisBController::class,'devisRepondu'])->name('devisb.repondu')->middleware('auth');
    //attente de reduction
    Route::get('/getAllObjetReduction/{id_devis}/{id_utilisateur}', [DevisBController::class,'getAllObjetAttenteReduction'])->name('devisb.allObjetReduction')->middleware('auth');
    Route::get('/getObjetForReduction/{id}', [DevisBController::class,'getObjetForReduction'])->name('devisb.getObjetReduction')->middleware('auth');
    Route::any('/ajoutReduction/{id}', [DevisBController::class,'ajouterReduction'])->name('devisb.ajouterReduction')->middleware('auth');
    //Accepter
    Route::get('/getAllDevisAccepter', [DevisBController::class,'getAllDevisAccepter'])->name('devisb.allDevisAccepter')->middleware('auth');

    //modif prix pour reduction
    Route::any('/changePriceObjet', [DevisBController::class,'updtPriceToObjet'])->name('devisb.updtPriceToObjet')->middleware('auth');
    //envoie reductionà l'utilisateur
    Route::get('/sendReductionToUser/{id_devis}/{id_utilisateur}', [DevisBController::class,'sendReduction'])->name('devisb.sendReduction')->middleware('auth');

    //assignation equipe
    Route::get('/redirectToAssignation/{id}', [DevisBController::class,'redirectionToAssignation'])->name('devisb.redirectionToAssignation')->middleware('auth');

    //Date Indisponible
    Route::any('/send_date', [DevisBController::class,'askDateChange'])->name('devisb.send_date')->middleware('auth');
});

//lIVRAISON
Route::group(['prefix' => 'livraison123456'], function () {
    Route::any('/attente', [LivraisonController::class,'assignationEquipe'])->name('livraisonb.assignation')->middleware('auth');
    Route::any('/liste', [LivraisonController::class,'index'])->name('livraisonb.liste')->middleware('auth');

    //Detail planifier
    Route::get('/detailPlanifier/{id}', [LivraisonController::class,'getDetailPlanifier'])->name('livraisonb.DetailPlanifier')->middleware('auth');
    Route::get('/suiviLivraison/{id}', [LivraisonController::class,'getSuiviLivraison'])->name('livraisonb.SuiviLivraison')->middleware('auth');
});

