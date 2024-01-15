<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Eps extends Model
{
    use HasFactory;

    protected $table = 'eps';

    protected $fillable = [
        'name',
        'address',
        'phone',
        'code',
        'contract_start_date',
        'contract_end_date'
    ];
}
