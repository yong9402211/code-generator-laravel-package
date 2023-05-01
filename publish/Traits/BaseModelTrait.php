<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

trait BaseModelTrait
{
    use  HasFactory, SoftDeletes;

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
