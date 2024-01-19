<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Eps extends Model
{
    use HasFactory;
    use SoftDeletes;

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
