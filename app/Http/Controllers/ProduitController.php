<?php

namespace App\Http\Controllers;

use App\Models\Produit;
use App\Http\Requests\StoreProduitRequest;
use App\Http\Requests\UpdateProduitRequest;
use App\Http\Resources\ProduitResource;
use Auth;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class ProduitController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {

            $Produits = Produit::all();

            return response()->json(
                [
                    'statut' => 'success',
                    'message' => "",
                    'data' => ProduitResource::collection($Produits),
                    'statutCode' => Response::HTTP_OK
                ],
                Response::HTTP_OK
            );
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(
                [
                    'statut' => 'error',
                    'message' => $th->getMessage(),
                    'errors' => []
                ],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProduitRequest $request)
    {
        DB::beginTransaction();
        try {

            $attributs = $request->all();

            $produit = Produit::create($attributs);
            DB::commit();
            return response()->json(
                [
                    'statut' => 'success',
                    'message' => "Produit creer",
                    'data' => new ProduitResource($produit),
                    'statutCode' => Response::HTTP_OK
                ],
                Response::HTTP_OK
            );
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollback();
            return response()->json(
                [
                    'statut' => 'error',
                    'message' => $th->getMessage(),
                    'errors' => []
                ],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }



        //return response()->json($Produit, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $Produit = Produit::find($id);

            if (!$Produit) {
                throw new Exception("Le produit avec l'ID {$id} est introuvable.", Response::HTTP_NOT_FOUND);
            }
            $user = Auth::user();
            if(!$user || $user->role->slug == 'client'){
                $Produit->nbreVue++;
                $Produit->save();
            }


            return response()->json(
                [
                    'statut' => 'success',
                    'message' => "",
                    'data' => new ProduitResource($Produit),
                    'statutCode' => Response::HTTP_OK
                ],
                Response::HTTP_OK
            );
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(
                [
                    'statut' => 'error',
                    'message' => $th->getMessage(),
                    'errors' => []
                ],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProduitRequest $request, string $id)
    {
        DB::beginTransaction();
        try {
            $Produit = Produit::find($id);

            if (!$Produit) {
                throw new Exception("Le produit avec l'ID {$id} est introuvable.", Response::HTTP_NOT_FOUND);
            }

            // Update only the 'nom' field, not 'slug'
            $Produit->update($request->all());
            DB::commit();
            return response()->json([
                'statut' => 'success',
                'message' => "Produit mis à jour",
                'data' => new ProduitResource($Produit),
                'statutCode' => Response::HTTP_OK
            ], Response::HTTP_OK);
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json([
                'statut' => 'error',
                'message' => $th->getMessage(),
                'errors' => [],
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        DB::beginTransaction();
        try {
            $Produit = Produit::find($id);

            if (!$Produit) {
                throw new Exception("Le produit avec l'ID {$id} est introuvable.", Response::HTTP_NOT_FOUND);
            }

            $Produit->delete();
            DB::commit();
            return response()->json([
                'statut' => 'success',
                'message' => "Produit supprimé",
                'data' => null,
                'statutCode' => Response::HTTP_OK
            ], Response::HTTP_OK);
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json([
                'statut' => 'error',
                'message' => $th->getMessage(),
                'errors' => [],
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function nouveaux()
    {
        try {

            $newProduits = Produit::latest('created_at')->take(20)->get();

            return response()->json(
                [
                    'statut' => 'success',
                    'message' => "",
                    'data' => ProduitResource::collection($newProduits),
                    'statutCode' => Response::HTTP_OK
                ],
                Response::HTTP_OK
            );
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(
                [
                    'statut' => 'error',
                    'message' => $th->getMessage(),
                    'errors' => []
                ],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }

    }
    public function tendances()
    {
        try {

            // Récupérer les 20 produits les plus vendus
            $trendingProducts = Produit::withCount([
                'commandes as total_sales' => function ($query) {
                    $query->select(DB::raw('SUM(commade_produits.quantite)'));
                }
            ])
                ->orderBy('total_sales', 'desc')
                ->take(20)
                ->get();

            return response()->json(
                [
                    'statut' => 'success',
                    'message' => "",
                    'data' => ProduitResource::collection($trendingProducts),
                    'statutCode' => Response::HTTP_OK
                ],
                Response::HTTP_OK
            );
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(
                [
                    'statut' => 'error',
                    'message' => $th->getMessage(),
                    'errors' => []
                ],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }

    }

    public function populaires()
    {
        try {

            $popularProducts = Produit::orderBy('nbreVue', 'desc')->take(20)->get();


            return response()->json(
                [
                    'statut' => 'success',
                    'message' => "",
                    'data' => ProduitResource::collection($popularProducts),
                    'statutCode' => Response::HTTP_OK
                ],
                Response::HTTP_OK
            );
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(
                [
                    'statut' => 'error',
                    'message' => $th->getMessage(),
                    'errors' => []
                ],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }

    }

}
