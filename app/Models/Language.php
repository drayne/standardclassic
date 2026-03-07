<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    protected $fillable = [
        'name',
        'code'
    ];

    public function articleTranslations()
    {
        return $this->hasMany(ArticleTranslation::class);
    }
}
