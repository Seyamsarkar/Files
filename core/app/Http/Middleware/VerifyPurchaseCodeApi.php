<?php

namespace App\Http\Middleware;

use Closure;

class VerifyPurchaseCodeApi
{
    public function handle($request, Closure $next)
    {
        $general = gs();
        if ($general->api == 1) {
            return $next($request);
        }
        $notify[] = ['error', 'Api feature is unavailable now'];
        if ($request->is('api/*')) {
            return response()->json([
                'status'  => 'error',
                'message' => $notify,
            ]);
        } else {
            return to_route('user.home')->withNotify($notify);
        }
    }
}
