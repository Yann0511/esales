<?php

namespace App\Http\Controllers;

use App\Models\Notation;
use App\Http\Requests\StoreNotationRequest;
use App\Http\Requests\UpdateNotationRequest;
use App\Http\Resources\NotationResource;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class NotationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $notations = Notation::all();

            return response()->json([
                'statut' => 'success',
                'message' => "",
                'data' => NotationResource::collection($notations),
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
    public function store(StoreNotationRequest $request)
    {
        DB::beginTransaction();
        try {
            $validated = $request->validated();

            $notation = Notation::create($validated);
            DB::commit();
            return response()->json([
                'statut' => 'success',
                'message' => "Notation créée",
                'data' => new NotationResource($notation),
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
            $notation = Notation::find($id);

            if (!$notation) {
                throw new Exception("La notation avec l'ID {$id} est introuvable.", Response::HTTP_NOT_FOUND);
            }

            return response()->json([
                'statut' => 'success',
                'message' => "",
                'data' => new NotationResource($notation),
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
    public function update(UpdateNotationRequest $request, string $id)
    {
        DB::beginTransaction();
        try {
            $notation = Notation::find($id);

            if (!$notation) {
                throw new Exception("La notation avec l'ID {$id} est introuvable.", Response::HTTP_NOT_FOUND);
            }

            $notation->update($request->validated());
            DB::commit();
            return response()->json([
                'statut' => 'success',
                'message' => "Notation mise à jour",
                'data' => new NotationResource($notation),
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
            $notation = Notation::find($id);

            if (!$notation) {
                throw new Exception("La notation avec l'ID {$id} est introuvable.", Response::HTTP_NOT_FOUND);
            }

            $notation->delete();
            DB::commit();
            return response()->json([
                'statut' => 'success',
                'message' => "Notation supprimée",
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
