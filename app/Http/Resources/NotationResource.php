<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class NotationResource extends JsonResource
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
            "note" => $this->note,
            "produitId" => $this->produitId,
            "userId" => $this->userId,
            "created_at" => !$this->created_at ? null : Carbon::parse($this->created_at)->translatedFormat('d/m/Y à H:i:s'),
            "updated_at" => !$this->updated_at ? null : Carbon::parse($this->updated_at)->translatedFormat('d/m/Y à H:i:s'),
        ];
    }
}
