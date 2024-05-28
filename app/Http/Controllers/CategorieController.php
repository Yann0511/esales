<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Response;
use App\Http\Requests\StoreCategorieRequest;
use App\Http\Requests\UpdateCategorieRequest;
use App\Models\Categorie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategorieController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        
         // Récupérer toutes les catégories de produits depuis la base de données
         try {

         $categories = Categorie::all();

         // Retourner les catégories sous forme de réponse JSON
         return response()->json([
            'statut' => 'success', 
            'message' => null, 
            'data' =>  $categories , 
            'statutCode' => Response::HTTP_OK], 
            Response::HTTP_OK);

    
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'statut' => 'error', 
                'message' => $th->getMessage(), 
                'errors' => []],
                 Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategorieRequest $request)
    {
        //
        DB::beginTransaction();
        try {
         
        // Vérifier si la catégorie existe déjà
        $categorieExistante = Categorie::where('nom', $request->input('nom'))->first();

        // Si la catégorie existe déjà, retourner une réponse d'erreur
        if ($categorieExistante) {
            return response()->json([
                'statut' => 'error', 
                'message' => 'La catégorie existe déjà.', 
                'errors' => [],
                'statutCode' => Response::HTTP_CONFLICT
            ], Response::HTTP_CONFLICT);
        }
        // Create a new category
        $categorie = Categorie::create([
            'nom' => $request->input('nom'),
            'icone' => $request->input('icone', 'default-icon.png')
        ]);
        DB::commit();

         return response()->json([
            'statut' => 'success', 
            'message' => null, 
            'data' =>  $categorie , 
            'statutCode' => Response::HTTP_OK], 
            Response::HTTP_OK);

    
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            return response()->json([
                'statut' => 'error', 
                'message' => $th->getMessage(), 
                'errors' => []],
                 Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        try {
          $categorie = Categorie::find($id);
          //  $categorie = Categorie::findOrFail($id);

            if (!$categorie) {
                throw new Exception("La categorie avec l'ID {$id} est introuvable.", Response::HTTP_NOT_FOUND);
            }
         // Return a specific category
        
         return response()->json([
            'statut' => 'success', 
            'message' => null, 
            'data' =>  $categorie , 
            'statutCode' => Response::HTTP_OK], 
            Response::HTTP_OK);
        } catch (\Throwable $th) {
            //throw $th;
         
            return response()->json([
                'statut' => 'error', 
                'message' => $th->getMessage(), 
                'errors' => []],
                 Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategorieRequest $request, string $id)
    {
        //
        DB::beginTransaction();
        try{
        
        $categorie = Categorie::find($id);

        if (!$categorie) {
            throw new Exception("La categorie avec l'ID {$id} est introuvable.", Response::HTTP_NOT_FOUND);
        }

        // Find and update the category
        $categorie = Categorie::findOrFail($id);
        $categorie->update($request->all());

        DB::commit();

        return response()->json([
           'statut' => 'success', 
           'message' => null, 
           'data' =>  $categorie , 
           'statutCode' => Response::HTTP_OK], 
           Response::HTTP_OK);

   
       } catch (\Throwable $th) {
           //throw $th;
           DB::rollBack();
           return response()->json([
               'statut' => 'error', 
               'message' => $th->getMessage(), 
               'errors' => []],
                Response::HTTP_INTERNAL_SERVER_ERROR);
       }
    }
    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        
        //

        // Recherche de la catégorie
        $categorie = Categorie::find($id);

        // Vérification si la catégorie existe
        if (!$categorie) {
            // Retourner une réponse d'erreur si la catégorie n'existe pas
            return response()->json([
                'statut' => 'error',
                'message' => 'La catégorie n\'existe pas.',
                'errors' => [],
                'statutCode' => Response::HTTP_NOT_FOUND
            ], Response::HTTP_NOT_FOUND);
          }
        DB::beginTransaction();
        try {
         // Find and delete the category
         $categorie = Categorie::findOrFail($id);
         

      $categorie->delete();

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
