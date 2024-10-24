<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;


class CustomerPocketHistory extends Model
{
    protected $table = 'customer_pocket_history';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'customer_id',
        'pocket_type',
        'order_id',
        'order_delivery_id',
        'note',
        'note_text',
        'recieve_pocket',
        'pocket_money',
        'pocket_present',
        'file',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at',
    ];
}

