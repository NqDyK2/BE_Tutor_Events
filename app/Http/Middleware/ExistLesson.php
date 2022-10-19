<?php

namespace App\Http\Middleware;

use App\Models\Lesson;
use Closure;
use Illuminate\Http\Request;

class ExistLesson
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
        $lesson = Lesson::find($request->lesson_id);

        if ($lesson === null) {
            return response([
                'message' => 'Buổi học không tồn tại'
            ], 404);
        }

        $request->attributes->add(['lesson' => $lesson]);

        return $next($request);
    }
}
