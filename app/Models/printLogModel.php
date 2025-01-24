<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class printLogModel extends Model
{
    use HasFactory;
    protected $table = 'print_log';
    protected $primaryKey = 'print_log_id';
    //public $timestamps = false;

    protected $fillable = [
     'print_log_type',
     'print_log_delivery_id',
     'print_log_order_id',
     'order_delivery_status',
     'print_log_count',
     'created_by',
     
    ];
    
     public function GetCount()
     {
        return $this->hasOne(Form::class,'name','print_log_type');
     }

     public function Delivery()
     {
        return $this->hasOne(OrderDelivery::class,'order_delivery_id','print_log_delivery_id');
     }
     public function User()
     {
        return $this->hasOne(users::class,'id','created_by');
     }

}
