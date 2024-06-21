<?php

namespace App\Http\Controllers;

use App\Models\Visitor;
use Illuminate\Http\Response;
use Carbon\Carbon;
use Illuminate\Http\Request;

class VisitorController extends Controller
{
    /**
     * Obtenir le nombre total de visiteurs.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function nombreVisiteurs()
    {
        try {
            $nombreVisiteurs = Visitor::count();

            return response()->json(['statut' => 'success', 'message' => 'Nombre total de visiteurs', 'data' => $nombreVisiteurs, 'statutCode' => Response::HTTP_OK], Response::HTTP_OK);
        } catch (\Throwable $th) {
            return response()->json(['statut' => 'error', 'message' => $th->getMessage(), 'errors' => []], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    /**
     * Ajouter une adresse IP à la base de données.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajouterAdresseIP(Request $request)
    {
        $request->validate([
            'ip_address' => 'required|ip',
        ]);

        try {
            $ipAddress = $request->input('ip_address');

            // Vérifier si l'adresse IP existe déjà
            $visitor = Visitor::where('ip_address', $ipAddress)->first();

            if ($visitor) {
                // Mettre à jour l'heure de visite
                $visitor->update(['visited_at' => now()]);
            } else {
                // Créer un nouvel enregistrement
                Visitor::create([
                    'ip_address' => $ipAddress,
                    'visited_at' => now(),
                ]);
            }

            return response()->json([
                'statut' => 'success',
                'message' => 'Adresse IP ajoutée ou mise à jour avec succès',
                'data' => null,
                'statutCode' => Response::HTTP_OK
            ], Response::HTTP_OK);
        } catch (\Throwable $th) {
            return response()->json(['statut' => 'error', 'message' => $th->getMessage(), 'errors' => []], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Obtenir le nombre de visiteurs pour l'année en cours.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function nombreVisiteursAnneeCourante()
    {
        try {
            $debutAnnee = Carbon::now()->startOfYear();
            $finAnnee = Carbon::now()->endOfYear();

            $nombreVisiteurs = Visitor::whereBetween('visited_at', [$debutAnnee, $finAnnee])->count();

            return response()->json([
                'statut' => 'success',
                'message' => 'Nombre de visiteurs pour l\'année en cours',
                'data' => $nombreVisiteurs,
                'statutCode' => Response::HTTP_OK
            ], Response::HTTP_OK);
        } catch (\Throwable $th) {
            return response()->json(['statut' => 'error', 'message' => $th->getMessage(), 'errors' => []], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
