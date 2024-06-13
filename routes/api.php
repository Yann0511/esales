<?php
use App\Http\Controllers\CommentaireController;
use App\Http\Controllers\CategorieController;
use App\Http\Controllers\PanierController;
use App\Http\Controllers\PanierProduitController;
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

    Route::group(['prefix' => 'produits', 'as' => 'produits.'], function () {

        Route::get('/nouveaux', 'ProduitController@nouveaux');

    });

    Route::group(['middleware' => ['auth:api']], function () {

        Route::group(['prefix' => 'authentificate', 'as' => 'auth.'], function () {

            Route::post('/logout', 'AuthController@logout');
        });

        Route::apiResource('roles', 'RoleController')->names('roles');

        Route::apiResource('categories', 'CategorieController')->names('categories');
        Route::get('/categories/{id}', [CategorieController::class, 'show']);


Route::apiResource('commentaires', CommentaireController::class);

        Route::apiResource('commandes', 'CommandeController')->names('commandes');

        Route::apiResource('notations', 'NotationController')->names('notations');

        Route::apiResource('paniers', PanierController::class);

        Route::apiResource('panier-produits', PanierProduitController::class);

        Route::apiResource('produits', 'ProduitController')->names('produits');

        Route::apiResource('users', 'UserController')->names('users');

        Route::group(['prefix' => 'user', 'as' => 'user.'], function () {

            Route::get('/{id}/statut', 'UserController@statut')->middleware('permission:desactiver.un.user');

        });

        /*Route::apiResource('permissions', 'PermissionController', ['only' => ['index']])->names('permissions');

        */
    });
});
