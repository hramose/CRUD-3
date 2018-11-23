<?php

namespace Sone\CRUD\Tests\Unit\Models;

use Sone\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\Model;

class AccountDetails extends Model
{
    use CrudTrait;

    protected $table = 'account_details';
    protected $fillable = ['user_id', 'nickname', 'profile_picture'];

    /**
     * Get the user for the account details.
     */
    public function user()
    {
        return $this->belongsTo('Sone\CRUD\Tests\Unit\Models\User');
    }

    public function addresses()
    {
        return $this->hasMany('Sone\CRUD\Tests\Unit\Models\Address');
    }
}
