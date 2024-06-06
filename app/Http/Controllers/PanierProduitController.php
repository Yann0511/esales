<?php

namespace App\Http\Controllers;

use App\Models\PanierProduit;
use App\Http\Requests\StorePanierProduitRequest;
use App\Http\Requests\UpdatePanierProduitRequest;
use App\Http\Resources\PanierProduitResource;
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

            $panierProduit = PanierProduit::create($validated);
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
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
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
