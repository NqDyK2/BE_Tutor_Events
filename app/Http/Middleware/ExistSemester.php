<?php

namespace App\Http\Middleware;

use App\Models\Semester;
use Closure;
use Illuminate\Http\Request;

class ExistSemester
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
        $semester = Semester::find($request->id);

        if($semester === null) {
            return response([
                'status' => false,
                'message' => 'kì học này không tồn tại'
            ], 404);
        }
        $request->attributes->add(['semester' => $semester]);

        return $next($request);
    }
}
