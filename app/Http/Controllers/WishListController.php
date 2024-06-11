<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Response;
use App\Http\Requests\StoreWishListRequest;
use App\Http\Requests\UpdateWishListRequest;
use App\Models\WishList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WishListController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $wishlists = WishList::with('user', 'produit')->get();

            return response()->json([
                'statut' => 'success', 
                'message' => null, 
                'data' => $wishlists, 
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
    public function store(StoreWishListRequest $request)
    {
        DB::beginTransaction();
        try {
            $wishlist = WishList::create($request->validated());

            DB::commit();

            return response()->json([
                'statut' => 'success', 
                'message' => null, 
                'data' => $wishlist, 
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
            $wishlist = WishList::with('user', 'produit')->find($id);

            if (!$wishlist) {
                throw new Exception("La WishList avec l'ID {$id} est introuvable.", Response::HTTP_NOT_FOUND);
            }

            return response()->json([
                'statut' => 'success', 
                'message' => null, 
                'data' => $wishlist, 
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
    public function update(UpdateWishListRequest $request, string $id)
    {
        DB::beginTransaction();
        try {
            $wishlist = WishList::find($id);

            if (!$wishlist) {
                throw new Exception("La WishList avec l'ID {$id} est introuvable.", Response::HTTP_NOT_FOUND);
            }

            $wishlist->update($request->validated());

            DB::commit();

            return response()->json([
                'statut' => 'success', 
                'message' => null, 
                'data' => $wishlist, 
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
        $wishlist = WishList::find($id);

        if (!$wishlist) {
            return response()->json([
                'statut' => 'error',
                'message' => 'La WishList n\'existe pas.',
                'errors' => [],
                'statutCode' => Response::HTTP_NOT_FOUND
            ], Response::HTTP_NOT_FOUND);
        }

        DB::beginTransaction();
        try {
            $wishlist->delete();

            DB::commit();

            return response()->json([
                'statut' => 'success',
                'message' => "WishList supprimée",
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
