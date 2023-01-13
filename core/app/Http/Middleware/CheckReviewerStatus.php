<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckReviewerStatus {
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle($request, Closure $next) {

        if (Auth::guard('reviewer')->check()) {
            $reviewer = authReviewer();

            if ($reviewer->status && $reviewer->ev && $reviewer->sv && $reviewer->tv) {
                return $next($request);
            } else {

                if ($request->is('api/*')) {
                    $notify[] = 'You need to verify your account first.';
                    return response()->json([
                        'remark'  => 'unverified',
                        'status'  => 'error',
                        'message' => ['error' => $notify],
                        'data'    => [
                            'is_ban'         => $reviewer->status,
                            'email_verified' => $reviewer->ev,
                        ],
                    ]);
                } else {
                    return to_route('reviewer.authorization');
                }

            }

        }

        abort(403);
    }

}
