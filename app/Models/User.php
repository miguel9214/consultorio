<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'users';


    protected $fillable = [
        'rol',
        'name',
        'email',
        'password',
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

    protected $hidden = [
        'password',
    ];
}
