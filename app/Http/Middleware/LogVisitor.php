<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Visitor;

class LogVisitor
{
    public function handle(Request $request, Closure $next)
    {
        // Obtenir l'adresse IP du visiteur
        $ipAddress = $request->ip();

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

        return $next($request);
    }
}
