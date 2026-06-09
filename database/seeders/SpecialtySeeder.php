<?php

namespace Database\Seeders;

use App\Models\Specialty;
use Illuminate\Database\Seeder;

class SpecialtySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $specialties = [
            [
                'name' => 'Dentist',
                'description' => 'Specialized dental care for preventive visits, cleanings, and oral health management.',
                'icon' => 'tooth',
            ],
            [
                'name' => 'Dermatology',
                'description' => 'Skin, hair, and nail care for acne, rashes, and cosmetic consultations.',
                'icon' => 'sparkles',
            ],
            [
                'name' => 'Cardiology',
                'description' => 'Heart and vascular wellness, diagnostics, and chronic cardiovascular care.',
                'icon' => 'heart',
            ],
            [
                'name' => 'Pediatrics',
                'description' => 'Child and adolescent care, routine wellness checks, and immunizations.',
                'icon' => 'child',
            ],
            [
                'name' => 'Neurology',
                'description' => 'Brain and nervous system care, including headaches, seizures, and neuro assessments.',
                'icon' => 'brain',
            ],
        ];

        foreach ($specialties as $specialty) {
            Specialty::updateOrCreate(
                ['name' => $specialty['name']],
                $specialty
            );
        }
    }
}
