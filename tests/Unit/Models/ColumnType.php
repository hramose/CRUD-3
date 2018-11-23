<?php

namespace Sone\CRUD\Tests\Unit\Models;

use Sone\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\Model;

class ColumnType extends Model
{
    use CrudTrait;

    protected $table = 'column_types';
}
