<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PanierResource extends JsonResource
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
            'userId' => $this->userId,
            //'articles' => PanierProduitResource::collection($this->produits),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
