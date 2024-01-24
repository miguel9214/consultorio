<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Consultationtype extends Model
{
    use HasFactory;

    protected $table = 'consultation_types';

    protected $fillable = [
        'name',
        'price',
    ];
}
