<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;


class Roles extends Model
{
    protected $table = 'roles';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'role_name',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at'
    ];
}

