<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use App\Models\Orders;

class OrderDelivery extends Model
{
    protected $table = 'order_delivery';
    protected $primaryKey = 'order_delivery_id';
    public $timestamps = false;

    protected $fillable = [
        'order_id',
        'order_delivery_number',
        'status',
        'status_payment',
        'file',
        'total',
        'money_deposit',
        'date_send',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at',
		'render_price',
		'remark_1',
		'remark_2',
		'remark_3',
		'remark_4',
		'remark_5',
    ];

    public function getOrder()
    {
        return $this->belongsTo(Orders::class,'order_id');
    }
}

