<?php

namespace App\Http\Controllers;

use App\Models\Panier;
use App\Models\PanierProduit;
use App\Http\Requests\StorePanierRequest;
use App\Http\Requests\UpdatePanierRequest;
use App\Http\Resources\PanierResource;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class PanierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $paniers = Panier::all();

            return response()->json([
                'statut' => 'success',
                'message' => "",
                'data' => PanierResource::collection($paniers),
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

    public function store(StorePanierRequest $request)
    {
        DB::beginTransaction();
        try {
            // Vérifier s'il existe déjà un panier pour cet utilisateur
            $existingPanier = Panier::where('userId', $request->userId)->first();

            if ($existingPanier) {
                throw new Exception("Cet utilisateur a déjà un panier.", Response::HTTP_BAD_REQUEST);
            }

            $validated = $request->validated();

            $panier = Panier::create($validated);
            DB::commit();
            return response()->json([
                'statut' => 'success',
                'message' => "Panier créé",
                'data' => new PanierResource($panier),
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
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $panier = Panier::with('produits')->find($id);

            if (!$panier) {
                throw new Exception("Le panier avec l'ID {$id} est introuvable.", Response::HTTP_NOT_FOUND);
            }

            return response()->json([
                'statut' => 'success',
                'message' => "",
                'data' => new PanierResource($panier),
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
     * Update the specified resource in storage.
     */
    public function update(UpdatePanierRequest $request, string $id)
    {
        DB::beginTransaction();
        try {
            $panier = Panier::find($id);

            if (!$panier) {
                throw new Exception("Le panier avec l'ID {$id} est introuvable.", Response::HTTP_NOT_FOUND);
            }

            $panier->update($request->validated());
            DB::commit();
            return response()->json([
                'statut' => 'success',
                'message' => "Panier mis à jour",
                'data' => new PanierResource($panier),
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
            $panier = Panier::find($id);

            if (!$panier) {
                throw new Exception("Le panier avec l'ID {$id} est introuvable.", Response::HTTP_NOT_FOUND);
            }

            $panier->delete();
            DB::commit();
            return response()->json([
                'statut' => 'success',
                'message' => "Panier supprimé",
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
