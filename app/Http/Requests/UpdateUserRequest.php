<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            //
            'nom' => 'sometimes|required|string|max:255',
            'prenoms' => 'sometimes|required|string|max:255',
            'telephone' => 'sometimes|required',
            'adresse' => 'sometimes|required',
            'roleId' => 'sometimes|required|exists:roles,id',
            'email' => 'sometimes|required|'
        ];
    }
}
