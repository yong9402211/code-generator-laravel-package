<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmployerUser extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'work_email', 'personal_email', 'password',
        'user_role', 'role_name', 'company_id',
        'ic_number', 'dob', 'phone_no',
        'company_start_date', 'first_time_login', 'position',
        'verification_code', 'full_name',
    ];

    protected $hidden = [
    ];

    protected $storetable = [
        'work_email', 'personal_email', 'password',
        'user_role', 'role_name', 'company_id',
        'ic_number', 'dob', 'phone_no',
        'company_start_date', 'first_time_login', 'position',
        'verification_code', 'full_name',
    ];

    protected $updateable = [
        'work_email', 'personal_email', 'password',
        'user_role', 'role_name', 'company_id',
        'ic_number', 'dob', 'phone_no',
        'company_start_date', 'first_time_login', 'position',
        'verification_code', 'full_name',
    ];
}
