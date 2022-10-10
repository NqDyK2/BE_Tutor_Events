<?php

namespace App\Http\Middleware;

use App\Models\Major;
use Closure;
use Illuminate\Http\Request;

class ExistMajor
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
        $major = Major::find($request->id);

        if($major === null) {
            return response([
                'message' => 'Chuyên ngành không tồn tại'
            ], 404);
        }
        $request->attributes->add(['major' => $major]);

        return $next($request);
    }
}
