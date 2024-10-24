<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;


class OrderDeliveryItems extends Model
{
    protected $table = 'order_delivery_items';
    protected $primaryKey = 'order_delivery_items_id';
    public $timestamps = false;

    protected $fillable = [
        'order_delivery_id',
        'order_items_id',
        'qty',
    ];
}

