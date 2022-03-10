<?php

namespace Database\Seeders;

use App\Models\Subject;
use App\Models\Teacher;
use App\Models\TeacherSubject;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class TeacherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        Teacher::truncate();
        Teacher::factory()->count(20)->create();

        $teachers = Teacher::all();
        $subjects = Subject::all();

        foreach($teachers as $index => $teacher) {
            $teacher->subjects()->attach($subjects[$index]);
            $teacher->save();
        }

        $teachers[0]->subjects()->attach($subjects[1]);
    }
}
