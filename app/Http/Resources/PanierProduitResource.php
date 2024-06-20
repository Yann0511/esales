<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PanierProduitResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'panierId' => $this->panierId,
            'produitId' => $this->produitId,
            'quantite' => $this->quantite,
            "produits" => $this->when($this->produits !== null, function () {
                return $this->produits->map(function ($produit) {
                    return [
                        "id" => $produit->id,
                        "nom" => $produit->nom,
                        "quantite" => $produit->pivot->quantite,
                    ];
                });
            }),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
