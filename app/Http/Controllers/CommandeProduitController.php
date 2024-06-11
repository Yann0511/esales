<?php

namespace App\Http\Controllers;

use App\Models\Produit;
use App\Models\Commande;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Exception;

class CommandeProduitController extends Controller
{
    public function create(Request $request, $commandeId)
    {
        try {
            // Début de la transaction
            DB::beginTransaction();

            // Récupère les données de la requête
            $produitId = $request->input('produit_id');
            $quantite = $request->input('quantite');

            // Vérifie si la commande existe
            $commande = Commande::find($commandeId);
            if (!$commande) {
                throw new Exception("La commande avec l'ID {$commandeId} est introuvable.", Response::HTTP_NOT_FOUND);
            }

            // Vérifie si le produit existe
            $produit = Produit::find($produitId);
            if (!$produit) {
                throw new Exception("Le produit avec l'ID {$produitId} est introuvable.", Response::HTTP_NOT_FOUND);
            }

            // Ajoute le produit à la commande avec la quantité spécifiée
            $commande->produits()->attach($produitId, ['quantite' => $quantite]);

            // Commit de la transaction si tout s'est bien passé
            DB::commit();

            return response()->json([
                'statut' => 'success',
                'message' => 'Produit ajouté à la commande avec succès',
                'data' => null,
                'statutCode' => Response::HTTP_OK
            ], Response::HTTP_OK);
        } catch (\Throwable $th) {
            // Rollback de la transaction en cas d'erreur
            DB::rollback();

            return response()->json([
                'statut' => 'error',
                'message' => $th->getMessage(),
                'errors' => [],
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function update(Request $request, $commandeId, $produitId)
    {
        try {
            // Début de la transaction
            DB::beginTransaction();

            // Récupère la quantité à mettre à jour
            $quantite = $request->input('quantite');

            // Vérifie si la commande existe
            $commande = Commande::find($commandeId);
            if (!$commande) {
                throw new Exception("La commande avec l'ID {$commandeId} est introuvable.", Response::HTTP_NOT_FOUND);
            }

            // Vérifie si le produit existe dans la commande
            $produit = $commande->produits()->where('produit_id', $produitId)->first();
            if (!$produit) {
                throw new Exception("Le produit avec l'ID {$produitId} n'existe pas dans la commande.", Response::HTTP_NOT_FOUND);
            }

            // Met à jour la quantité du produit dans la commande
            $commande->produits()->updateExistingPivot($produitId, ['quantite' => $quantite]);

            // Commit de la transaction si tout s'est bien passé
            DB::commit();

            return response()->json([
                'statut' => 'success',
                'message' => 'Quantité du produit mise à jour avec succès dans la commande',
                'data' => null,
                'statutCode' => Response::HTTP_OK
            ], Response::HTTP_OK);
        } catch (\Throwable $th) {
            // Rollback de la transaction en cas d'erreur
            DB::rollback();

            return response()->json([
                'statut' => 'error',
                'message' => $th->getMessage(),
                'errors' => [],
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function delete($commandeId, $produitId)
    {
        try {
            // Début de la transaction
            DB::beginTransaction();

            // Vérifie si la commande existe
            $commande = Commande::find($commandeId);
            if (!$commande) {
                throw new Exception("La commande avec l'ID {$commandeId} est introuvable.", Response::HTTP_NOT_FOUND);
            }

            // Supprime le produit de la commande
            $commande->produits()->detach($produitId);

            // Commit de la transaction si tout s'est bien passé
            DB::commit();

            return response()->json([
                'statut' => 'success',
                'message' => 'Produit supprimé de la commande avec succès',
                'data' => null,
                'statutCode' => Response::HTTP_OK
            ], Response::HTTP_OK);
        } catch (\Throwable $th) {
            // Rollback de la transaction en cas d'erreur
            DB::rollback();

            return response()->json([
                'statut' => 'error',
                'message' => $th->getMessage(),
                'errors' => [],
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
