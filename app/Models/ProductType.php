<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;


class ProductType extends Model
{
    protected $table = 'product_type';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'product_type_name',
    ];
}

