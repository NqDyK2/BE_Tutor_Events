<?php

namespace App\Http\Middleware;

use App\Models\ClassStudent;
use Closure;
use Illuminate\Http\Request;

class ExistClassStudent
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
        $classStudent = ClassStudent::find($request->id);

        if ($classStudent === null) {
            return response([
                'message' => 'Sinh viên không tồn tại'
            ], 404);
        }

        $request->attributes->add(['classStudent' => $classStudent]);

        return $next($request);
    }
}
