<?php

namespace App\Console\Commands\Dev;

use App\Models\Attendance;
use App\Models\Classroom;
use App\Models\Semester;
use Illuminate\Console\Command;

class fakeAttendance extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'devfake:attendance';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $semesterId = $this->ask('Enter semesterId');
        $semester = Semester::find($semesterId);

        if (!$semester) {
            return $this->error('Semester not found');
        }

        $classrooms = Classroom::where('semester_id', $semesterId)
            ->with('lessons', function ($q) {
                $q->select(['id', 'classroom_id', 'attended']);
            })
            ->with('classStudents', function ($q) {
                $q->select(['classroom_id', 'student_email']);
            })
            ->get();

        foreach ($classrooms as $classroom) {
            logger($classroom->classStudents);
            if ($classroom->lessons->count() && $classroom->classStudents->count()) {
                foreach ($classroom->lessons as $lesson) {
                    if ($lesson->attended) {
                        return;
                    }

                    $lesson->update([
                        'attended' => 1
                    ]);

                    foreach ($classroom->classStudents as $student) {
                        if (rand(0, 1)) {
                            Attendance::create([
                                'student_email' => $student->student_email,
                                'lesson_id' => $lesson->id,
                                'status' => Attendance::STATUS_PRESENT
                            ]);
                        }
                    }
                }
            }
        }

        return Command::SUCCESS;
    }
}
