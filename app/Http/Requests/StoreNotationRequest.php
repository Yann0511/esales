<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreNotationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules()
    {
        return [
            'note' => 'required|integer|min:1|max:5',
            'produitId' => 'required|exists:produits,id',
            'userId' => 'required|exists:users,id',
        ];
    }
}
