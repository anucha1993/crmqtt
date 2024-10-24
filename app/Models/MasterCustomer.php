<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

use App\Models\CustomerPocketHistory;


class MasterCustomer extends Model
{
    protected $table = 'master_customer';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'id',
        'text_number_vat',
        'store_name',
        'customer_name',
        'customer_phone',
        'customer_mail',
        'customer_address',
        'province_code',
        'amphoe_code',
        'district_code',
        'zip_code',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at',
    ];

    public function PocketMoney()
    {
        return $this->belongsTo(CustomerPocketHistory::class,'id','customer_id')->orderby('created_at','asc');
    }
}

