<?php

namespace App\Models;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use LogsActivity;

    protected $fillable = [
        'name',
        'slug',
    ];

    public function articles()
    {
        return $this->hasMany(Article::class);
    }
}
