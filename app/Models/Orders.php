<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use App\Models\PaymentHistory;


class Orders extends Model
{
    protected $table = 'orders';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'quotation_id',
        'customer_type',
        'customer_id',
        'order_number',
        'status',
        'status_send',
        'status_payment',
        'on_vat',
        'vat',
        'price_all',
        'total',
        'note',
        'payment_type',
        'file',
		'remark_1',
		'remark_2',
		'remark_3',
		'remark_4',
		'remark_5',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at',
		'delivery_location_id',
		'payment_method_type_code',
    ];

    public function GetDeposit()
    {
        return $this->belongsTo(PaymentHistory::class,'id','order_id')->where('status',1);
    }

    public function GetDepositSum()
{
    return $this->GetDeposit()->sum('total');
}


}

