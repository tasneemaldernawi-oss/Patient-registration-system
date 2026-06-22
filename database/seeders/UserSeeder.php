<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Doctor; // Make sure to import your Doctor model!

class UserSeeder extends Seeder
{
    public function run()
    {
        // 1. Create Admin Account
        User::create([
            'name' => 'Admin Test',
            'email' => 'admin@system.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        // 2. Create Receptionist Account
        User::create([
            'name' => 'Sara Ahmed',
            'email' => 'receptionist@system.com',
            'password' => Hash::make('password123'),
            'role' => 'receptionist',
        ]);

        // 3. Create the Doctor's Login Account first
        $doctorUser = User::create([
            'name' => 'Dr. Ali',
            'email' => 'doctor@system.com',
            'password' => Hash::make('password123'),
            'role' => 'doctor',
        ]);

        // 4. Immediately link him to his department profile using his new ID
        // Note: Change 'Doctor::create' to match your profile model name if it's different
        Doctor::create([
            'user_id'    => $doctorUser->id, // This links it directly to id 4
            'department' => 'dentist',       // This tells the system he's a dentist!
        ]);
        
    }
}