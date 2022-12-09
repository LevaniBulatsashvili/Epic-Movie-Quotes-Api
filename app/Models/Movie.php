<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\HasTranslations;

class Movie extends Model
{
    use HasFactory, HasTranslations;

    protected $guarded = ['id'];

	public $translatable = ['name', 'director', 'description'];

	public function quote(): HasMany
	{
		return $this->hasMany(Quote::class);
	}

    public function genre(): HasMany
    {
        return $this->hasMany(Genre::class);
    }
}
