<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Response;
use App\Traits\Helpers\ResponseJsonTrait;

class CheckRole
{
    use ResponseJsonTrait;

    protected $auth;
    /**
     * Creates a new instance of the middleware.
     *
     * @param Guard $auth
     */
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $role)
    {
        if ($this->auth->guest() || ! $request->user()->hasRole($role)) {
            return $this->errorResponse("Vous n'avez pas les droits d'accès à cette resource", [], Response::HTTP_UNAUTHORIZED);
        }

        return $next($request);
    }
}
