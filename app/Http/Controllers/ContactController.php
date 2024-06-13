<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Http\Requests\StoreContactRequest;
use App\Http\Resources\ContactResource;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {

            $Contacts = Contact::all();

            return response()->json(
                [
                    'statut' => 'success',
                    'message' => "",
                    'data' => ContactResource::collection($Contacts),
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

        //return response()->json($Contacts);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreContactRequest $request)
    {
        DB::beginTransaction();
        try {

            $Contact = Contact::create($request->all());
            DB::commit();
            return response()->json(
                [
                    'statut' => 'success',
                    'message' => "Contact creer",
                    'data' => new ContactResource($Contact),
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



        //return response()->json($Contact, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $Contact = Contact::find($id);

            if (!$Contact) {
                throw new Exception("Le Contact avec l'ID {$id} est introuvable.", Response::HTTP_NOT_FOUND);
            }

            return response()->json(
                [
                    'statut' => 'success',
                    'message' => "",
                    'data' => new ContactResource($Contact),
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
        // $Contact = Contact::findOrFail($id);
        // return response()->json($Contact);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        DB::beginTransaction();
        try {
            $Contact = Contact::find($id);

            if (!$Contact) {
                throw new Exception("Le Contact avec l'ID {$id} est introuvable.", Response::HTTP_NOT_FOUND);
            }

            $Contact->delete();
            DB::commit();
            return response()->json([
                'statut' => 'success',
                'message' => "Contact supprimé",
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
