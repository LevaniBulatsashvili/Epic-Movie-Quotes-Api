<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Translatable\HasTranslations;

class Genre extends Model
{
    use HasFactory, HasTranslations;

    public $translatable = ['genre'];

    protected $guarded = ['id'];

    public function movie(): BelongsTo
	{
		return $this->belongsTo(Movie::class);
	}
}
