<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Response;
use App\Traits\Helpers\ResponseJsonTrait;

class CheckPermissions
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
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @param $permissions
     * @return mixed
     */
    public function handle($request, Closure $next, $permissions)
    {
        if (! is_array($permissions)) {
            $permissions = explode("|", $permissions);
        }

        if ($this->auth->guest() || ! $request->user()->hasPermission($permissions)) {
            return $this->errorResponse(trans('api.Vous-n-avez-pas-la-permission-d-accès-à-cette-resource'), [], Response::HTTP_UNAUTHORIZED);
        }

        return $next($request);
    }
}
