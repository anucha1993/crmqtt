<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryLocation extends Model
{
    use HasFactory;
	
	protected $table = 'master_delivery_location';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'id',
		'customer_id',
		'order_id',
		'order_delivery_id',
		'location',
		'quotation_id',
		'onsite_contact_name',
		'onsite_contact_phone_no',
    ];
}
