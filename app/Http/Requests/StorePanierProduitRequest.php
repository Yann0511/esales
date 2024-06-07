<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePanierProduitRequest extends FormRequest
{
    public function rules()
    {
        return [
            'panierId' => 'required|exists:paniers,id',
            'produitId' => 'required|exists:produits,id',
            'quantite' => 'required|integer|min:1',
        ];
    }
}
