<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;


class ProductSize extends Model
{
    protected $table = 'product_size';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'prosuct_type_id',
        'product_size_name',
    ];
}

