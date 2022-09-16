<?php

namespace App\Http\Middleware;

use App\Models\Issue;
use Closure;
use Illuminate\Http\Request;

class ExistIssue
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
        $issue = Issue::find($request->id);

        if($issue === null) {
            return response([
                'status' => false,
                'message' => 'Issue not exist'
            ],404);
        }

        $request->attributes->add(['issue' => $issue]);
        
        return $next($request);
    }
}
