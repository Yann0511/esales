<?php

namespace App\Http\Controllers;

use App\Models\Commande;
use App\Http\Requests\StoreCommandeRequest;
use App\Http\Requests\UpdateCommandeRequest;
use App\Http\Resources\CommandeResource;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class CommandeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $commandes = Commande::all();

            return response()->json([
                'statut' => 'success',
                'message' => "",
                'data' => CommandeResource::collection($commandes),
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
    public function store(StoreCommandeRequest $request)
    {
        DB::beginTransaction();
        try {
            $validated = $request->validated();

            $commande = Commande::create($validated);
            DB::commit();
            return response()->json([
                'statut' => 'success',
                'message' => "Commande créée",
                'data' => new CommandeResource($commande),
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
            $commande = Commande::find($id);

            if (!$commande) {
                throw new Exception("La commande avec l'ID {$id} est introuvable.", Response::HTTP_NOT_FOUND);
            }

            return response()->json([
                'statut' => 'success',
                'message' => "",
                'data' => new CommandeResource($commande),
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
    public function update(UpdateCommandeRequest $request, string $id)
    {
        DB::beginTransaction();
        try {
            $commande = Commande::find($id);

            if (!$commande) {
                throw new Exception("La commande avec l'ID {$id} est introuvable.", Response::HTTP_NOT_FOUND);
            }

            $commande->update($request->validated());
            DB::commit();
            return response()->json([
                'statut' => 'success',
                'message' => "Commande mise à jour",
                'data' => new CommandeResource($commande),
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
            $commande = Commande::find($id);

            if (!$commande) {
                throw new Exception("La commande avec l'ID {$id} est introuvable.", Response::HTTP_NOT_FOUND);
            }

            $commande->delete();
            DB::commit();
            return response()->json([
                'statut' => 'success',
                'message' => "Commande supprimée",
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
