<?php

namespace App\Models;


use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable
{
    //

    use HasFactory, Notifiable;

    protected $table= 'admins';
    protected $primaryKey = 'id';

    protected $fillable =[
        'name',
        'password'
    ];

    protected $hidden =[
 
       'password',
       'remember_token',
    ];

    
}
