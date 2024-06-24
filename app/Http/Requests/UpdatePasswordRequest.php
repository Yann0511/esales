<?php

namespace App\Http\Requests\auth;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePasswordRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'new_password' => 'required|string|confirmed|min:8|max:12',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'email.required'         => 'Veuillez préciser l\'email de l\'utilisateur.',
            'email.email'            => 'Format d\'email incorrect. Veuillez le corrigez.',
            'new_password.required' => 'Veuillez précisez votre nouveau mot de passe actuel',
            'new_password.min' => 'Le nouveau mot de passe doit contenir au moins 8 caractères',
            'new_password.max' => 'Le nouveau mot de passe doit contenir au maximun 12 caractères',
            'new_password.confirmed' => 'Le mot de passe de confirmation doit correspondre au nouveau mot de passe',
        ];
    }
}
