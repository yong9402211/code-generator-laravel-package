<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BaseModel extends Model
{
    use HasFactory, SoftDeletes;

    public $incrementing = false;

    protected $keyType = 'string';

    protected static $storable = [];

    protected static $updatable = [];

    public static function getStorable()
    {
        return static::$storable;
    }

    public static function getUpdatable()
    {
        return static::$updatable;
    }
}
