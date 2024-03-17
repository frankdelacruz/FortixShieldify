<?php

namespace Fortix\Shieldify\Middleware;

use Closure;
use Illuminate\Http\Request;
use Fortix\Shieldify\Services\ShieldifyService;

class CheckRole
{
    protected $shieldifyService;

    public function __construct(ShieldifyService $shieldifyService)
    {
        $this->shieldifyService = $shieldifyService;
    }

    public function handle(Request $request, Closure $next, $role)
    {
        if (!$this->shieldifyService->setUser($request->user())->hasRole($role)) {
            // Redirect or abort depending on your application needs
            return response('Unauthorized.', 403);
        }

        return $next($request);
    }
}
