<?php

namespace Sone\CRUD\Tests\Unit\Models;

use Sone\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use CrudTrait;

    protected $table = 'users';
    protected $fillable = ['name', 'email', 'password'];

    /**
     * Get the account details associated with the user.
     */
    public function accountDetails()
    {
        return $this->hasOne('Sone\CRUD\Tests\Unit\Models\AccountDetails');
    }

    /**
     * Get the articles for this user.
     */
    public function articles()
    {
        return $this->hasMany('Sone\CRUD\Tests\Unit\Models\Article');
    }

    /**
     * Get the user roles.
     */
    public function roles()
    {
        return $this->belongsToMany('Sone\CRUD\Tests\Unit\Models\Role', 'user_role');
    }
}
