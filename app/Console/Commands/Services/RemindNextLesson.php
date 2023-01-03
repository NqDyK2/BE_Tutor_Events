<?php

namespace App\Console\Commands\Services;

use App\Jobs\Mail\SendMailRemindLesson;
use App\Models\Lesson;
use App\Models\User;
use Illuminate\Console\Command;

class RemindNextLesson extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'services:remindNextLesson';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $lessons = Lesson::select('id', 'classroom_id', 'start_time', 'end_time')
            ->where('start_time', '>=', now()->addDay()->startOfDay())
            ->where('start_time', '<=', now()->addDay()->endOfDay())
            ->get();

        if (!$lessons) {
            return;
        }

        foreach ($lessons as $lesson) {
            foreach ($lesson->classroom-> classStudents as $student) {
                if ($student->is_joined) {
                    SendMailRemindLesson::dispatch(
                        $student->student_email,
                        [
                            'lesson' => $lesson->toArray(),
                            'subject' => $lesson->subject->toArray(),
                        ]
                    );
                }
            }
        }

        return Command::SUCCESS;
    }
}
