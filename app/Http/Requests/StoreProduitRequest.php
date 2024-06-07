<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProduitRequest extends FormRequest
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
            'nom' => 'required|string|max:255',
            'description' => 'required|string',
            'prix' => 'required',
            'qte' => 'required|integer|min:0',
            'categorieId' =>'required|exists:categories,id',
            'photos' => 'required|array|min:1',
            'photos.*' => 'distinct|file'

        ];
    }
}
