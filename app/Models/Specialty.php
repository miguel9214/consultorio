<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Specialty extends Model
{
    use HasFactory;


    public function medicos()
    {
        return $this->belongsToMany(Medico::class, 'doctor_speciality', 'speciality_id', 'doctor_id');
    }
}
