<?php

namespace Database\Seeders;

use App\Models\Subject;
use Illuminate\Database\Seeder;

class SubjectSeeder extends Seeder
{
    public function run(): void
    {
        $subjects = [
            // SSC Subjects
            [
                'name' => 'Mathematics',
                'class' => 'SSC'
            ],
            [
                'name' => 'Physics',
                'class' => 'SSC'
            ],
            [
                'name' => 'Chemistry',
                'class' => 'SSC'
            ],
            [
                'name' => 'Biology',
                'class' => 'SSC'
            ],
            [
                'name' => 'English',
                'class' => 'SSC'
            ],

            // HSC Subjects
            [
                'name' => 'Higher Math I',
                'class' => 'HSC'
            ],
            [
                'name' => 'Higher Math II',
                'class' => 'HSC'
            ],
            [
                'name' => 'Physics I',
                'class' => 'HSC'
            ],
            [
                'name' => 'Physics II',
                'class' => 'HSC'
            ],
            [
                'name' => 'Chemistry I',
                'class' => 'HSC'
            ],
            [
                'name' => 'Chemistry II',
                'class' => 'HSC'
            ],
            [
                'name' => 'Biology I',
                'class' => 'HSC'
            ],
            [
                'name' => 'Biology II',
                'class' => 'HSC'
            ],
            [
                'name' => 'English I',
                'class' => 'HSC'
            ],
            [
                'name' => 'English II',
                'class' => 'HSC'
            ],
            [
                'name' => 'ICT',
                'class' => 'HSC'
            ],
        ];

        foreach ($subjects as $subject) {
            Subject::create($subject);
        }
    }
}