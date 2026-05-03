<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Lecturer
        User::create([
            'name' => 'Dr. Sarah Ahmad',
            'email' => 'lecturer@expo.com',
            'password' => Hash::make('password'),
            'role' => 'lecturer',
        ]);

        // Students
        $students = [
            ['name' => 'Ahmad Faris', 'email' => 'student1@expo.com'],
            ['name' => 'Nurul Ain', 'email' => 'student2@expo.com'],
            ['name' => 'Haziq Danial', 'email' => 'student3@expo.com'],
            ['name' => 'Liyana Sofea', 'email' => 'student4@expo.com'],
        ];

        foreach ($students as $student) {
            User::create([
                'name' => $student['name'],
                'email' => $student['email'],
                'password' => Hash::make('password'),
                'role' => 'student',
            ]);
        }
    }
}