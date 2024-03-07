<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Medicine extends Model
{
    use HasFactory;

    protected $table = 'medicines';

    protected $fillable = [
        'code',
        'name',
        'dosage',
        'dosing_frequency',
        'indications',
        'contraindications',
        'administration_method',
        'pharmaceutical_laboratory',
        'price',
    ];
}
