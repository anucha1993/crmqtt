<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Amphoe extends Model
{
    protected $table = 'master_amphoe';
    public $timestamps = false;

    protected $fillable = [
        'amphoe_name',
        'amphoe_code',
        'province_code',
    ];
}

