<?php

namespace App\Http\Middleware;

use App\Models\Classroom;
use Closure;
use Illuminate\Http\Request;

class ExistClassroom
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
        $classroom = Classroom::find($request->id);

        if ($classroom === null) {
            return response([
                'status' => false,
                'message' => 'Lớp học này không tồn tại'
            ], 404);
        }

        $request->attributes->add(['classroom' => $classroom]);

        return $next($request);
    }
}
