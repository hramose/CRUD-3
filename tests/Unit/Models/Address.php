<?php

namespace Sone\CRUD\Tests\Unit\Models;

use Sone\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use CrudTrait;

    protected $table = 'addresses';
    protected $fillable = ['city', 'street', 'number'];

    /**
     * Get the author for the article.
     */
    public function accountDetails()
    {
        return $this->belongsTo('Sone\CRUD\Tests\Unit\Models\AccountDetails');
    }
}
