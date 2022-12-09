<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Spatie\Translatable\HasTranslations;

class Quote extends Model
{
    use HasFactory, HasTranslations;

	protected $guarded = ['id'];

	public $translatable = ['body'];

	public function movie(): HasOne
	{
		return $this->hasOne(Movie::class);
	}

    public function quoteLike(): HasMany
    {
        return $this->hasMany(QuoteLike::class);
    }

    public function quoteComment(): HasMany
    {
        return $this->hasMany(QuoteComment::class);
    }
}
