<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Medico extends Model
{
    use HasFactory;

    protected $table = 'doctors';

    protected $fillable = [
        'person_id'
    ];

    public function person()
    {
        return $this->belongsTo(Person::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }


    public function especialidades()
    {
        return $this->belongsToMany(Specialty::class, 'doctor_speciality', 'doctor_id', 'speciality_id');
    }

}
