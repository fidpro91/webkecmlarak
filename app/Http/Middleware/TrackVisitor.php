<?php

namespace App\Http\Middleware;

use App\Models\Visitor;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TrackVisitor
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Hanya lacak request GET non-ajax dan bukan halaman admin / api / up
        if ($request->isMethod('GET') 
            && !$request->ajax() 
            && !$request->is('admin*', 'api*', 'up', 'livewire*', 'storage*')) {
            Visitor::track($request);
        }

        return $next($request);
    }
}
