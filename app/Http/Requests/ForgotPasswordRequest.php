<?php

namespace App\Http\Requests;

class ForgotPasswordRequest extends ApiFormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => 'required|email|exists:users,email',
        ];
    }

    public function attributes(): array
    {
        return [
            'email' => 'e-mail',
        ];
    }
}
