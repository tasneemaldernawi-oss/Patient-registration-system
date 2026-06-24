<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Patient extends Model
{
    use HasFactory;
    protected $table = 'patients';
    protected $primaryKey = 'id';

    protected $fillable = [
        'name',
        'date_of_birth',
        'gender',
        'phone',
        
    ];
    protected $casts = [
        'date_of_birth' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
    public function appointments() {
        return $this->hasMany(Appointment::class);
    }

}
