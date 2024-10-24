<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;



class Notifications extends Model
{
    protected $table = 'notifications';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'item_id',
        'noti_type',
        'items_status',
        'read',
        'noti_status',
        'on_vat',
        'created_by',
        'created_at',
        'updated_by',
        'updated_at',
    ];

}

