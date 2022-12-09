<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMovieRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name_en'      => 'required|max:255',
			'name_ka'      => 'required|max:255',
			'director_en'     => 'required|max:255',
			'director_ka'     => 'required|max:255',
            'description_en' => 'required|max:255',
            'description_ka' => 'required|max:255',
			// 'image'    => 'image|mimes:jpeg,png,jpg',
        ];
    }
}
