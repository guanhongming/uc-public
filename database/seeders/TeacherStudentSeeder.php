<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\ClassGroup;
use Illuminate\Support\Facades\Hash;

class TeacherStudentSeeder extends Seeder
{

    public function run(): void
    {

        $teacher = User::factory()->create([
            'name' => 'Mr. teacher1',
            'email' => 'teacher1@example.com',
            'password' => Hash::make('password'),
            'role' => 'teacher',
        ]);

        $student1 = User::factory()->create(['name' => 'Alice', 'email' => 'student1@example.com', 'password' => Hash::make('password')]);
        $student2 = User::factory()->create(['name' => 'Bob', 'email' => 'bob@example.com', 'password' => Hash::make('password')]);
        $student3 = User::factory()->create(['name' => 'Charlie', 'email' => 'charlie@example.com', 'password' => Hash::make('password')]);

        $groupA = ClassGroup::create(['name' => 'A2 Physics']);
        $groupB = ClassGroup::create(['name' => 'T homeroom']);

        $teacher->classGroups()->attach([$groupA->id, $groupB->id]);

        $groupA->users()->attach([$student1->id, $student2->id]);
        $groupB->users()->attach([$student1->id, $student3->id]);

        $year2024Group = ClassGroup::create(['name' => 'Year 2024']);
        $year2025Group = ClassGroup::create(['name' => 'Year 2025']);

        $student4 = User::factory()->create(['name' => 'David', 'email' => 'david@example.com', 'password' => Hash::make('password')]);
        $student5 = User::factory()->create(['name' => 'Eve', 'email' => 'eve@example.com', 'password' => Hash::make('password')]);  


        $year2024Group->users()->attach([$student1->id, $student2->id]);


        $year2025Group->users()->attach([$student3->id, $student4->id, $student5->id]);



         $groupA->users()->attach([$student4->id]);
        $groupB->users()->attach([$student5->id]);

    }
}