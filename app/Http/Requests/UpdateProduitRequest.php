<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProduitRequest extends FormRequest
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
            'nom' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'prix' => 'sometimes',
            'qte' => 'sometimes|integer|min:0',
            'categorieId' =>'sometimes|exists:categories,id',
            'photos' => 'sometimes|array|min:1',
            'photos.*' => 'sometimes|distinct|file'

        ];
    }
}
