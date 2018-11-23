<?php

namespace Sone\CRUD\Tests\Unit\Models;

use Sone\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use CrudTrait;

    protected $table = 'roles';
    protected $fillable = ['name'];

    /**
     * Get the user for the account details.
     */
    public function user()
    {
        return $this->belongsToMany('Sone\CRUD\Tests\Unit\Models\User', 'user_role');
    }
}
