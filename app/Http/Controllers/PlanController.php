<?php namespace App\Http\Controllers;

use App\Category;
use App\Http\Requests;
use App\Plan;
use App\Subcategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Stripe\Charge;
use Stripe\Customer;

class PlanController extends Controller
{
    function __construct()
    {
        $this->middleware('Payment');
        // TODO: Implement __construct() method.
    }


    public function show()
    {
        if(\Auth::user()->hasRole('Provider')) {
            $id = \Crypt::encrypt(\Auth::user()->stripe_id);
            $plans = \Auth::user()->plans;
            $provider_cat_subcat = \Auth::user()->provider->ppivot;
            $cat_id = [];
            $subcat_id = [];
            foreach($provider_cat_subcat as $catsub) {
                $cat_id [] = (unserialize(json_decode($catsub->category_id)));
                $subcat_id [] = (unserialize(json_decode($catsub->subcategory_id)));
            }

            $categories = Category::whereIn('id', $cat_id[0] )->get();
            $subcategories = Subcategory::whereIn('id', $subcat_id[0] )->get();

            return view('Plan.create', compact('id','plans', 'categories', 'subcategories'));
        } else {
            \Session::flash('message', 'To buy a plan become a provider');
            return \Redirect::to('/profile');
        }

    }

    public function create(Request $request)
    {
        \Stripe\Stripe::setApiKey('sk_test_3ljJklZR39vyq2JBaNUnB5Ir');
        $customer = \Stripe\Customer::retrieve(\Crypt::decrypt(\Input::get('customer_id')));

        $plan = \Input::get('plan_id');
        $money = "";

        $validator = \Validator::make(
            $request->all()
            , [
            'customer_id' => 'required',
            'plan_id' => 'required'
        ]);

        if ($validator->fails()) {
            return \Response::make("Require Customer ID", '500');
        } else {
            switch ($plan) {
                case 1:
                    $money = 499;
                    break;
                case 2:
                    $money = 999;
                    break;
                case 3:
                    $money = 2499;
                    break;
                default:
                    $money = 0;
            }

            try {
                $charges = Charge::create([
                    'amount' => $money,
                    'customer' => $customer->id,
                    'currency' => 'usd'
                ]);
                $plan = new Plan();
                $plan->user_id = \Auth::id();
                $plan->user->stripe_active = 1;
                $plan->user->save();
                $plan->plan_type = \Input::get('plan_type');
                $plan->plan_price = $charges->amount;
                $plan->balance = $charges->amount;
                $plan->auto_renew = \Input::get('auto_renew');
                $plan->click_cost = 20;
                $plan->impression_cost = 4;
                $plan->object = $charges->object;
                $plan->charge_id = $charges->id;
                $plan->paid = $charges->paid;
                $plan->status = $charges->status;
                $plan->amount = $charges->amount;
                $plan->currency = $charges->currency;
                $plan->balance_transaction = $charges->balance_transaction;
                $plan->active = 1;

                if ($plan->save()) {
                    \Session::set('plan_id', $plan->id);
                    \Session::remove("customer_id");
                    \Session::remove("plan_type");
                    return \Response::make(\Crypt::encrypt($plan->id), 200);
                }
            } catch(\Stripe\Error\Card $e) {
                return \Response::make("Sorry : " . $e->getMessage(), 400);
            }
        }
    }

    public function checkout()
    {
        \Stripe\Stripe::setApiKey('sk_test_3ljJklZR39vyq2JBaNUnB5Ir');
        $token = \Input::get('stripeToken');
        if (empty($token)) {
            return \Redirect::to('/');
        } else {
            $user = \Auth::user();
            if ($user->stripe_id == "" || is_null($user->stripe_id) || empty($user->stripe_id)) {
                $customer = Customer::create([
                    'description' => 'test',
                    'source' => $token,
                    'email' => \Auth::user()->email
                ]);
                $user->stripe_id = $customer->id;
                $user->card_id = $customer->default_source;
                if ($user->save()) {
                    \Session::flash('message', 'Card Verifcation Completed');
                    \Session::set("customer_id", $customer->id);
                    \Session::set("plan_type", Input::get('plan_type'));
                    return \Redirect::to('/premium');
                }
            } elseif(is_null($user->card_id) || empty($user->card_id)) {
                $customer = Customer::retrieve($user->stripe_id);
                $ch = $customer->sources->create([
                    'source' => $token
                ]);
                \Auth::user()->card_id = $ch->id;
                \Auth::user()->last_four = $ch->last4;
                \Auth::user()->save();
            }
            else {
                return \Redirect::to('/premium')->withInput(['auto_renew' => \Input::get('auto_renew')]);
            }
        }
    }

}
