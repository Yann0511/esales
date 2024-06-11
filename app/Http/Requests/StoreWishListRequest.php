<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreWishListRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Vous pouvez ajouter des vérifications d'autorisation ici
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'userId' => 'required|exists:users,id',
            'produitId' => 'required|exists:produits,id',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     */
    public function messages(): array
    {
        return [
            'userId.required' => 'L\'ID de l\'utilisateur est requis.',
            'userId.exists' => 'L\'ID de l\'utilisateur doit exister dans la table users.',
            'produitId.required' => 'L\'ID du produit est requis.',
            'produitId.exists' => 'L\'ID du produit doit exister dans la table produits.',
        ];
    }
}
