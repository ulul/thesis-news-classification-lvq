<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NewsCategory extends Model
{
    /**
     * Define all column in table news_categories
     *
     * @var array
     */
    protected $fillable = [
        'category'
    ];
}
