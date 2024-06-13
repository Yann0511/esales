<?php

use App\Http\Controllers\CommentaireController;
use App\Http\Controllers\CategorieController;
use App\Http\Controllers\CommandeController;
use App\Http\Controllers\NotationController;
use App\Http\Controllers\PanierController;
use App\Http\Controllers\PanierProduitController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\WishListController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use \OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *     title="Esales backend",
 *     version="1.0.0",
 *     description="Description de mon API Laravel"
 * )
 */

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => ['cors', 'json.response'], 'as' => 'api.'], function () {

    Route::post('/login', 'AuthController@login')->name('auth.login');

    Route::apiResource('contacts', 'ContactController')->only('store')->names('contacts');

    Route::apiResource('produits', 'ProduitController')->only('show')->names('produits');

    Route::group(['prefix' => 'produits', 'as' => 'produits.'], function () {

        Route::get('/nouveaux', 'ProduitController@nouveaux');

        Route::get('/tendances', 'ProduitController@tendances');

        Route::get('/populaires', 'ProduitController@populaires');

        Route::get('/populaires', 'ProduitController@populaires');
    });

    Route::group(['middleware' => ['auth:api']], function () {

        Route::group(['prefix' => 'authentificate', 'as' => 'auth.'], function () {

            Route::post('/logout', 'AuthController@logout');
        });

        Route::apiResource('roles', 'RoleController')->names('roles');
        Route::post('/admin/role', [RoleController::class, 'store'])->name('admin.role.store');
        Route::put('/admin/role/{id}', [RoleController::class, 'update'])->name('admin.role.update');
        Route::delete('/admin/role/{id}', [RoleController::class, 'destroy'])->name('admin.role.destroy');

        Route::apiResource('categories', 'CategorieController')->names('categories');
        Route::get('/categories/{id}', [CategorieController::class, 'show']);

        Route::apiResource('commentaires', CommentaireController::class);

        Route::apiResource('commandes', 'CommandeController')->names('commandes');
        Route::get('/commande', [CommandeController::class, 'getUserCommandes'])->name('commandes.user.index');
        Route::post('/commandes', [CommandeController::class, 'storeWithProducts'])->name('commandes.store');
        Route::put('/commandes/annuler/{id}', [CommandeController::class, 'cancelUserCommande'])->name('commandes.user.cancel');
        Route::put('/commandes/statut/{id}', [CommandeController::class, 'updateStatut'])->name('commandes.update.statut');
        Route::get('/commandes', [CommandeController::class, 'index'])->name('commandes.index');

        Route::apiResource('notations', 'NotationController')->names('notations');
        Route::get('/produits/{id}/notation', [NotationController::class, 'getProductNotations']);
        Route::post('/produits/{id}/notation', [NotationController::class, 'storeProductNotation']);

        Route::apiResource('paniers', PanierController::class);
        Route::post('panier/ajouter', [PanierProduitController::class, 'ajouter'])->name('panier.ajouter');
        Route::delete('panier/supprimer/{id}', [PanierProduitController::class, 'supprimer'])->name('panier.supprimer');
        Route::put('panier/modifier_qte/{id}', [PanierProduitController::class, 'modifierQte'])->name('panier.modifier_qte');
        Route::get('panier', [PanierController::class, 'listerProduits'])->name('panier.lister_produits');


        Route::apiResource('panier-produits', PanierProduitController::class)->except('show');

        Route::apiResource('produits', 'ProduitController')->except('show')->names('produits');

        Route::apiResource('produits', 'ProduitController')->except(['sotre', 'update'])->names('produits');

        Route::apiResource('wishlists', WishListController::class)->names('wishlists');

        Route::apiResource('users', 'UserController')->names('users');

        Route::group(['prefix' => 'user', 'as' => 'user.'], function () {

            Route::get('/{id}/statut', 'UserController@statut')->middleware('permission:desactiver.un.user');
        });

        /*Route::apiResource('permissions', 'PermissionController', ['only' => ['index']])->names('permissions');
        */
    });
});
