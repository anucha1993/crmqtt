<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\Roles;

class Users extends Model
{

    protected $table = 'users';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'role_id',
        'name',
        'status',
        'email',
        'email_verified_at',
        'password',
        'avatar',
        'remember_token',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at',
    ];


    public function GetRole()
    {
        return $this->belongsTo(Roles::class,'role_id');
    }
}
