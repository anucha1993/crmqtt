<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;


class Quotation extends Model
{
    protected $table = 'quotation';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'customer_type',
        'customer_id',
        'quotation_number',
        'status',
        'total',
        'note',
		'remark_1',
		'remark_2',
		'remark_3',
		'remark_4',
		'remark_5',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at',
    ];
}

