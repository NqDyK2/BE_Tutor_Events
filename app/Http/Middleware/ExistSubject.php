<?php

namespace App\Http\Middleware;

use App\Models\Subject;
use Closure;
use Illuminate\Http\Request;

class ExistSubject
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
        $subject = Subject::find($request->subject_id);

        if ($subject === null) {
            return response([
                'message' => 'Môn học không tồn tại'
            ], 404);
        }
        $request->attributes->add(['subject' => $subject]);

        return $next($request);
    }
}
