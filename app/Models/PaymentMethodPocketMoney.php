<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentMethodPocketMoney extends Model
{
    use HasFactory;
	
	protected $table = 'payment_method_pocket_money';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'id',
        'order_id',
		'amount',
		'created_by',
        'updated_by',
        'created_at',
        'updated_at',
    ];

}
