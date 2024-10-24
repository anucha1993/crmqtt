<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;


class District extends Model
{
    protected $table = 'master_district';
    public $timestamps = false;

    protected $fillable = [
        'district_name',
        'district_code',
        'amphoe_code',
        'province_code',
        'zipcode',
    ];
}

