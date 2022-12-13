<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreQuoteRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'username' => 'required',
            'quote_en'     => 'required|max:255',
            'quote_ka'     => 'required|max:255',
            'thumbnail'    => 'required|image|mimes:jpeg,png,jpg',
            'user_thumbnail'
        ];
    }
}
