<?php

namespace App\Http\Middleware;

use App\Models\Lesson;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckTeacher
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $isTeacher = Lesson::join('classrooms', 'classrooms.id', 'lessons.classroom_id')
            ->join('semesters', 'semesters.id', 'classrooms.semester_id')
            ->where('semesters.start_time', '<=', now())->where('semesters.end_time', '>=', now())
            ->where('lessons.teacher_email', Auth::user()->email)
            ->exists();

        if (!$isTeacher) {
            return response([
                'message' => 'Bạn không phải giáo viên'
            ], 403);
        }
        return $next($request);
    }
}
