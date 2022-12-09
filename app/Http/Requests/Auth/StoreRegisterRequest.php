<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class StoreRegisterRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'username'              => 'required|min:3|max:15|unique:users,username',
            'email'                 => 'required|max:255|email|unique:users,email',
            'password'              => 'required|min:8|max:15|confirmed',
            'password_confirmation' => 'required|min:8|max:15',
        ];
    }
}
