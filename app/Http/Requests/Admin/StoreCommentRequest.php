<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreCommentRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'username' => 'required|max:255',
            'body' => 'required|max:255',
            // 'image'    => 'required|image|mimes:jpeg,png,jpg',
        ];
    }
}
