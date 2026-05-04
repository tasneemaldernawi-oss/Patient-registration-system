<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Doctor extends Model
{
    use HasFactory;

    protected $table= 'doctors';
    protected $primaryKey = 'id';
    
    protected $fillable= [
        'name',
        'speciality',
        'email',
        'experience',
        'address'
    ];
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
    public function appointments(){
        return $this->hasMany(Appointment::class);
    }

}
