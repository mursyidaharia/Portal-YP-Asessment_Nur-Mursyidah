<?php

namespace Database\Seeders;

use App\Models\Classes;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Database\Seeder;

class SubjectSeeder extends Seeder
{
    public function run(): void
    {
        $lecturer = User::where('email', 'lecturer@expo.com')->first();
        $class1 = Classes::where('name', 'CS101')->first();
        $class2 = Classes::where('name', 'CS102')->first();

        $math = Subject::create([
            'name' => 'Mathematics',
            'created_by' => $lecturer->id,
        ]);

        $programming = Subject::create([
            'name' => 'Programming',
            'created_by' => $lecturer->id,
        ]);

        $dataStructure = Subject::create([
            'name' => 'Data Structure',
            'created_by' => $lecturer->id,
        ]);

        // Assign subjects to classes
        $class1->subjects()->attach([$math->id, $programming->id]);
        $class2->subjects()->attach([$programming->id, $dataStructure->id]);
    }
}