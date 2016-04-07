<?php namespace App\Http\Middleware;

use App\Adv_Management;
use Closure;

class Advertise {

	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{
        // Advertise show to all users either loggedin or not but don't show to those users who placed premium
        // Advertise show by range of user example if provider provide service by state then premium show by states
        // if provide by range then show by range and if nation then show to entire US.

        dd(Adv_Management::all()->random(2));

		return $next($request);
	}

}
