<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class BaseModel extends Authenticatable implements JWTSubject
{
    use HasFactory, SoftDeletes;

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

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [
            'exp' => time() + 86400 // 1 day expiration time
        ];
    }
}
