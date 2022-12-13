<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class StoreResetPasswordRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'token'    => 'required',
            'email'    => 'required|max:255|email',
            'password'              => 'required|min:8|max:15|confirmed',
            'password_confirmation' => 'required|min:8|max:15',
        ];
    }
}
