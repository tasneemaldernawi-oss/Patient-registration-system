<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Specialty extends Model
{
    use HasFactory;

    protected $table = 'specialties';
    protected $primaryKey = 'id';

    protected $fillable = [
        'name',
        'description',
        'icon',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function doctors()
    {
        return $this->hasMany(Doctor::class);
    }
}
