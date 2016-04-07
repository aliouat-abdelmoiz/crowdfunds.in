<?php namespace App\Http\Middleware;

use App;
use App\User;
use Closure;

class JobsMW {

	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{
        if(!User::find(\Auth::id())->hasRole('User')) {
			\Session::flash('message', 'You must become a provider');
            return \Redirect::to('/profile');
        }
		return $next($request);
	}

}
