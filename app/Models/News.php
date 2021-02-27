<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    /**
     * Define all column in table news
     *
     * @var array
     */
    protected $fillable = [
        'title', 'body', 'category_id'
    ];

    /**
     * Relation to news category
     *
     * @return void
     */
    public function category()
    {
        return $this->belongsTo(NewsCategory::class);
    }
}
