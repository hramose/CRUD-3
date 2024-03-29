<?php

namespace Sone\CRUD\Tests\Unit\Models;

use Sone\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use CrudTrait;

    protected $table = 'articles';
    protected $fillable = ['user_id', 'content', 'metas', 'tags', 'extras', 'cast_metas', 'cast_tags', 'cast_extras'];
    protected $casts = [
        'cast_metas' => 'object',
        'cast_tags' => 'object',
        'cast_extras' => 'object',
    ];

    /**
     * Get the author for the article.
     */
    public function user()
    {
        return $this->belongsTo('Sone\CRUD\Tests\Unit\Models\User');
    }
}
