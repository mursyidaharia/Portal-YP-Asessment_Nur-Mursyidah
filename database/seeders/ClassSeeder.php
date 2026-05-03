<?php

namespace Database\Seeders;

use App\Models\Classes;
use App\Models\User;
use Illuminate\Database\Seeder;

class ClassSeeder extends Seeder
{
    public function run(): void
    {
        $class1 = Classes::create(['name' => 'CS101']);
        $class2 = Classes::create(['name' => 'CS102']);

        // Assign students to classes
        $student1 = User::where('email', 'student1@expo.com')->first();
        $student2 = User::where('email', 'student2@expo.com')->first();
        $student3 = User::where('email', 'student3@expo.com')->first();
        $student4 = User::where('email', 'student4@expo.com')->first();

        // CS101 — student 1 & 2
        $class1->students()->attach([$student1->id, $student2->id]);

        // CS102 — student 3 & 4
        $class2->students()->attach([$student3->id, $student4->id]);
    }
}