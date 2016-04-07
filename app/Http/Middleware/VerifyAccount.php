<?php namespace App\Http\Middleware;

use App\User;
use Closure;

class VerifyAccount {

	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{
        if(\Auth::check()) {
            $account = User::find(\Auth::id(), ['activate']);
            if($account->activate == 0) {
                \Session::set('activate','0');
            } else {
                \Session::set("activate", "1");
            }
        }
		return $next($request);
	}

}
