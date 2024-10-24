<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;


class OrderItmes extends Model
{
    protected $table = 'order_itmes';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'quotation_id',
        'order_id',
		'product_id',
        'product_type_id',
        'product_size_id',
        'product_name',
        'size_unit',
        'price',
        'total_item',
        'number_order',
        'count_unit',
        'note',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at',
		'pera',
    ];
}

