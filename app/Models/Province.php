<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;


class Province extends Model
{
    protected $table = 'master_province';
    public $timestamps = false;

    protected $fillable = [
        'province_name',
        'province_code',
    ];
}

