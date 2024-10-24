<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;


class PaymentHistory extends Model
{
    protected $table = 'payment_history';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'order_delivery_id',
        'order_id',
        'payment_type',
        'status',
		'amount',
        'total',
        'date_playment',
        'file',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at',
    ];
}

