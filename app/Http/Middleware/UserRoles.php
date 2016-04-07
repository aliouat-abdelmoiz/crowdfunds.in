<?php namespace App\Http\Middleware;

use App\Message;
use App\Plan;
use App\User;
use Auth;
use Cartalyst\Stripe\Exception\CardErrorException;
use Cartalyst\Stripe\Stripe;
use Closure;
use Illuminate\Support\Str;
use Laravel\Cashier\Customer;
use Stripe\Error\Card;
use Stripe\Error\InvalidRequest;

class UserRoles {

	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{
        if(Auth::check()) {
            if(Auth::user()->hasRole('User')) {
                \Session::set('AccountType', 'user');
            } elseif(Auth::user()->hasRole('Supplier')) {
                \Session::set('AccountType', 'supplier');
            } elseif(Auth::user()->hasRole('Provider')) {
                \Session::set('AccountType', 'provider');
            } elseif(Auth::user()->hasRole('Admin')) {
                \Session::set('AccountType', 'admin');
            } else {
                return 'No Roles';
            }
        } else {
            return "You Should Login First";
        }

        $plans = Auth::user()->plans;

        if($plans->count() == 0) {
            Auth::user()->stripe_active = 0;
            Auth::user()->save();
        }

		return $next($request);
	}

}
