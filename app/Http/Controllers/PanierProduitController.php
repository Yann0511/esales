<?php

namespace App\Http\Controllers;

use App\Models\PanierProduit;
use App\Http\Requests\StorePanierProduitRequest;
use App\Http\Requests\UpdatePanierProduitRequest;
use App\Http\Resources\PanierProduitResource;
use App\Models\Panier;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class PanierProduitController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $panierProduits = PanierProduit::all();

            return response()->json([
                'statut' => 'success',
                'message' => "",
                'data' => PanierProduitResource::collection($panierProduits),
                'statutCode' => Response::HTTP_OK
            ], Response::HTTP_OK);
        } catch (\Throwable $th) {
            return response()->json([
                'statut' => 'error',
                'message' => $th->getMessage(),
                'errors' => []
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePanierProduitRequest $request)
    {
        DB::beginTransaction();
        try {
            $validated = $request->validated();

            // Vérifier si le produit existe déjà dans le panier
            $panierProduit = PanierProduit::where('panierId', $validated['panierId'])
                ->where('produitId', $validated['produitId'])
                ->first();

            if ($panierProduit) {
                // Si le produit existe, incrémenter la quantité
                $panierProduit->quantite += $validated['quantite'];
                $panierProduit->save();
            } else {
                // Sinon, créer une nouvelle entrée
                $panierProduit = PanierProduit::create($validated);
            }

            DB::commit();
            return response()->json([
                'statut' => 'success',
                'message' => "Produit ajouté au panier",
                'data' => new PanierProduitResource($panierProduit),
                'statutCode' => Response::HTTP_OK
            ], Response::HTTP_OK);
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json([
                'statut' => 'error',
                'message' => $th->getMessage(),
                'errors' => []
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePanierProduitRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            $panierProduit = PanierProduit::find($id);

            if (!$panierProduit) {
                throw new Exception("Le produit dans le panier avec l'ID {$id} est introuvable.", Response::HTTP_NOT_FOUND);
            }

            $panierProduit->update($request->validated());
            DB::commit();
            return response()->json([
                'statut' => 'success',
                'message' => "Produit dans le panier mis à jour",
                'data' => new PanierProduitResource($panierProduit),
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
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $panierProduit = PanierProduit::find($id);

            if (!$panierProduit) {
                throw new Exception("Le produit dans le panier avec l'ID {$id} est introuvable.", Response::HTTP_NOT_FOUND);
            }

            $panierProduit->delete();
            DB::commit();
            return response()->json([
                'statut' => 'success',
                'message' => "Produit retiré du panier",
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
}
