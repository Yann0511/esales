<?php

namespace App\Http\Resources\user\auth;

use App\Http\Resources\PaysResource;
use App\Http\Resources\RoleResource;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class AuthResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            "id" => $this->id,
            "nom" => $this->nom,
            "prenoms" => $this->prenoms,
            "statut" => $this->statut,
            "email" => $this->email,
            "adresse" => $this->adresse,
            "dernierConnexion" => $this->dernierConnexion,
            "role" => new RoleResource($this->role),
            "telephone" => $this->telephone,
            "created_at" => !$this->updated_at ? null : Carbon::parse($this->created_at)->translatedFormat('d/m/Y à H:i:s'),
            "updated_at" => !$this->updated_at ? null : Carbon::parse($this->updated_at)->translatedFormat('d/m/Y à H:i:s'),
        ];

    }
}
