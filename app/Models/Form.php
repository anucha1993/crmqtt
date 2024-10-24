<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Form extends Model
{
    use HasFactory;
    protected $table = 'forms';
    protected $primaryKey = 'id';


    protected $fillable = [
        'name',
        'description',
        'print_count',
    ];
}
