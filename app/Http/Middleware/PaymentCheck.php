<?php namespace App\Http\Middleware;

use Auth;
use Closure;
use Stripe\Error\InvalidRequest;

class PaymentCheck {

	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{
		\Stripe\Stripe::setApiKey('sk_test_3ljJklZR39vyq2JBaNUnB5Ir');
		if(\Auth::user()->stripe_id != "" || !empty(\Auth::user()->stripe_id)) {
			$customer = \Stripe\Customer::retrieve(Auth::user()->stripe_id);
			try {
				$customer->sources->retrieve(Auth::user()->card_id);
			} catch (InvalidRequest $e) {
				Auth::user()->card_id = "";
				Auth::user()->save();
			}
		}

		return $next($request);
	}

}
