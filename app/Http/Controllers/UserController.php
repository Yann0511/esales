<?php

namespace App\Http\Controllers;

use App\Http\Resources\user\auth\AuthResource;
use App\Jobs\SendEmailJob;
use App\Models\User;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {

            $Users = User::all();

            return response()->json(
                [
                    'statut' => 'success',
                    'message' => "",
                    'data' => AuthResource::collection($Users),
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
    public function store(StoreUserRequest $request)
    {
        DB::beginTransaction();
        try {

            $attributs = $request->all();

            $password = strtoupper($this->hashId(6)); // Générer le mot de passe

            $attributs = array_merge($attributs, [
                'password' => $password,
                'photo' => 'default.png',
                'statut' => 1
            ]);

            $User = User::create($attributs);
            DB::commit();

            dispatch(new SendEmailJob($User, "confirmation-compte", $password));

            return response()->json(
                [
                    'statut' => 'success',
                    'message' => "User creer",
                    'data' => new AuthResource($User),
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

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $User = User::find($id);

            if (!$User) {
                throw new Exception("Le User avec l'ID {$id} est introuvable.", Response::HTTP_NOT_FOUND);
            }

            return response()->json(
                [
                    'statut' => 'success',
                    'message' => "",
                    'data' => new AuthResource($User),
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
    public function update(UpdateUserRequest $request, string $id)
    {
        DB::beginTransaction();
        try {
            $User = User::find($id);

            if (!$User) {
                throw new Exception("Le User avec l'ID {$id} est introuvable.", Response::HTTP_NOT_FOUND);
            }

            // Update only the 'nom' field, not 'slug'
            $User->update($request->all());
            DB::commit();
            return response()->json([
                'statut' => 'success',
                'message' => "User mis à jour",
                'data' => new AuthResource($User),
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
            $User = User::find($id);

            if (!$User) {
                throw new Exception("Le User avec l'ID {$id} est introuvable.", Response::HTTP_NOT_FOUND);
            }

            $User->delete();
            DB::commit();
            return response()->json([
                'statut' => 'success',
                'message' => "User supprimé",
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

    public function statut(string $id)
    {
        try {
            $User = User::find($id);

            if (!$User) {
                throw new Exception("Le User avec l'ID {$id} est introuvable.", Response::HTTP_NOT_FOUND);
            }

            $User->statut = $User->statut ? 0 : 1 ;
            $User->save();

            return response()->json(
                [
                    'statut' => 'success',
                    'message' => "",
                    'data' => new AuthResource($User),
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
