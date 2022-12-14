<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreMovieRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'user_id' => 'required',
            'name_en'      => 'required|max:255',
            'name_ka'      => 'required|max:255',
            'director_en'     => 'required|max:255',
            'director_ka'     => 'required|max:255',
            'description_en' => 'required|max:255',
            'description_ka' => 'required|max:255',
            'thumbnail'    => 'required|image|mimes:jpeg,png,jpg',
        ];
    }
}
