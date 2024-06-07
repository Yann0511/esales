<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Response;
use App\Http\Requests\StoreCommentaireRequest;
use App\Http\Requests\UpdateCommentaireRequest;
use App\Models\Commentaire;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CommentaireController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $commentaires = Commentaire::with('produit', 'photos', 'user')->get();

            return response()->json([
                'statut' => 'success', 
                'message' => null, 
                'data' => $commentaires, 
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
    public function store(StoreCommentaireRequest $request)
    {
        DB::beginTransaction();
        try {
            $commentaire = Commentaire::create($request->validated());

            DB::commit();

            return response()->json([
                'statut' => 'success', 
                'message' => null, 
                'data' => $commentaire, 
                'statutCode' => Response::HTTP_CREATED
            ], Response::HTTP_CREATED);

        } catch (\Throwable $th) {
            DB::rollBack();

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
            $commentaire = Commentaire::with('produit', 'photos', 'user')->find($id);

            if (!$commentaire) {
                throw new Exception("Le commentaire avec l'ID {$id} est introuvable.", Response::HTTP_NOT_FOUND);
            }

            return response()->json([
                'statut' => 'success', 
                'message' => null, 
                'data' => $commentaire, 
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
    public function update(UpdateCommentaireRequest $request, string $id)
    {
        DB::beginTransaction();
        try {
            $commentaire = Commentaire::find($id);

            if (!$commentaire) {
                throw new Exception("Le commentaire avec l'ID {$id} est introuvable.", Response::HTTP_NOT_FOUND);
            }

            $commentaire->update($request->validated());

            DB::commit();

            return response()->json([
                'statut' => 'success', 
                'message' => null, 
                'data' => $commentaire, 
                'statutCode' => Response::HTTP_OK
            ], Response::HTTP_OK);

        } catch (\Throwable $th) {
            DB::rollBack();

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
        $commentaire = Commentaire::find($id);

        if (!$commentaire) {
            return response()->json([
                'statut' => 'error',
                'message' => 'Le commentaire n\'existe pas.',
                'errors' => [],
                'statutCode' => Response::HTTP_NOT_FOUND
            ], Response::HTTP_NOT_FOUND);
        }

        DB::beginTransaction();
        try {
            $commentaire->delete();

            DB::commit();

            return response()->json([
                'statut' => 'success',
                'message' => "Commentaire supprimé",
                'data' => null,
                'statutCode' => Response::HTTP_OK
            ], Response::HTTP_OK);

        } catch (\Throwable $th) {
            DB::rollBack();

            return response()->json([
                'statut' => 'error',
                'message' => $th->getMessage(),
                'errors' => []
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
