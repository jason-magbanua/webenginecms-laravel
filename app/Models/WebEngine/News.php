<?php

namespace App\Models\WebEngine;

use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    protected $connection = 'muonline';
    protected $table = 'WEBENGINE_NEWS';
    protected $primaryKey = 'news_id';
    public $timestamps = false;

    protected $fillable = [
        'news_title', 'news_content', 'news_author',
        'news_date', 'news_category', 'news_views',
    ];

    protected $casts = [
        'news_date' => 'datetime',
    ];
}
