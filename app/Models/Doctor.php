<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Doctor extends Model
{
    use HasFactory;

    protected $table = 'doctors';
    protected $primaryKey = 'id';

    protected $fillable = [
        'name',
        'phone_number',
        'speciality',
        'specialty_id',
        'email',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
    public function schedules()
    {
        return $this->hasMany(DoctorSchedule::class, 'doctor_id');
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function specialtyProfile()
    {
        return $this->belongsTo(Specialty::class, 'specialty_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
