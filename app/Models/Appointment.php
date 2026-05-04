<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Appointment extends Model
{
    use HasFactory;

    protected $table = 'appointments';
    protected $primaryKey = 'id';

    protected $fillable = [
        'patient_id',
        'doctor_id',
        'date',
        'time',
        'reason',
        'status'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function patient(){
        return $this->belongsTo(Appointment::class);
    }
    public function doctor(){
        return $this->belongsTo(Appointment::class);
    }
}
