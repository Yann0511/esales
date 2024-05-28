<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Http\Requests\StoreRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use App\Http\Resources\RoleResource;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {

            $roles = Role::all();

            return response()->json(
                [
                    'statut' => 'success',
                    'message' => "",
                    'data' => RoleResource::collection($roles),
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

        //return response()->json($roles);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRoleRequest $request)
    {
        DB::beginTransaction();
        try {
            $validated = $request->validated();
            $validated['slug'] = strtolower(str_replace(' ', '.', $validated['nom']));

            $role = Role::create($request->validated());
            DB::commit();
            return response()->json(
                [
                    'statut' => 'success',
                    'message' => "Role creer",
                    'data' => new RoleResource($role),
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



        //return response()->json($role, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $role = Role::find($id);

            if (!$role) {
                throw new Exception("Le rôle avec l'ID {$id} est introuvable.", Response::HTTP_NOT_FOUND);
            }

            return response()->json(
                [
                    'statut' => 'success',
                    'message' => "",
                    'data' => new RoleResource($role),
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
        // $role = Role::findOrFail($id);
        // return response()->json($role);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRoleRequest $request, string $id)
    {
        DB::beginTransaction();
        try {
            $role = Role::find($id);

            if (!$role) {
                throw new Exception("Le rôle avec l'ID {$id} est introuvable.", Response::HTTP_NOT_FOUND);
            }

            // Update only the 'nom' field, not 'slug'
            $role->update($request->validated());
            DB::commit();
            return response()->json([
                'statut' => 'success',
                'message' => "Role mis à jour",
                'data' => new RoleResource($role),
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
            $role = Role::find($id);

            if (!$role) {
                throw new Exception("Le rôle avec l'ID {$id} est introuvable.", Response::HTTP_NOT_FOUND);
            }

            $role->delete();
            DB::commit();
            return response()->json([
                'statut' => 'success',
                'message' => "Role supprimé",
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
