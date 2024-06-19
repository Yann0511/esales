<?php

namespace App\Http\Controllers;

use App\Models\Visitor;
use Illuminate\Http\Response;

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
}
