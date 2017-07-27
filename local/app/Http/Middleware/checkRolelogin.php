<?php
namespace App\Http\Middleware;
use Closure;
use Auth;
class checkRolelogin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(!Auth::guest())
        {
            if(Auth::user()->hasRole('user'))
            {
                return redirect('/dashboard');
            }
        }
        return $next($request);
    }
}
