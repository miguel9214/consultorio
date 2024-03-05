<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Prescription extends Model
{
    use HasFactory;

    protected $table = 'prescriptions';

    protected $fillable = [
        'date_prescription',
        'medicine',
        'dose',
        'treatment',
        'additional_instructions',
        'consultation_id',
    ];
}
