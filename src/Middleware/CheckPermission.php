<?php

namespace Fortix\Shieldify\Middleware;

use Closure;
use Illuminate\Http\Request;
use Fortix\Shieldify\Services\ShieldifyService;

class CheckPermission
{
    protected $shieldifyService;

    public function __construct(ShieldifyService $shieldifyService)
    {
        $this->shieldifyService = $shieldifyService;
    }

    public function handle(Request $request, Closure $next, $permission)
    {
        // Assuming permissions might have spaces, e.g., "update post"
        $permission = str_replace('_', ' ', $permission);

        if (!$this->shieldifyService->setUser($request->user())->hasPermission($permission)) {
            // Redirect or abort depending on your application's needs
            return response('Unauthorized.', 403);
        }

        return $next($request);
    }

}
