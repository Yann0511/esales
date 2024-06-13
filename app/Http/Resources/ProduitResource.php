<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProduitResource extends JsonResource
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
            'nom' => $this->nom,
            'description' => $this->description,
            'prix' => $this->prix,
            'qte' => $this->qte,
            'note' => $this->note(),
            'categorie' => new CategorieResource($this->categorie),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
