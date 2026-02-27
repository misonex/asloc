<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Support\AssociationContext;

class SetAssociationContext
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        if ($user && ! $user->hasRole('admin')) {
            app(AssociationContext::class)
                ->set($user->association_id);
        }

        return $next($request);
    }
}