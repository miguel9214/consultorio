<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
    use HasFactory;

    public $table = 'persons';

    protected $fillable = [
        'type_document',
        'document',
        'first_name',
        'last_name',
        'sex',
        'phone',
        'birthdate',
        'address',
        'city',
        'state',
        'neighborhood',
        'created_by_user',
        'updated_by_user'
    ];
}
