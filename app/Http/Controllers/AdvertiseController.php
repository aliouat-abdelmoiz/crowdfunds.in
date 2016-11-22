<?php
namespace App\Http\Controllers;

use App\Adv_Management;
use App\Http\Requests\AdvertiseRequest;
use App\Notification;
use App\Plan;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Input;
use Image;
use Stripe\Charge;
use Stripe\Customer;

class AdvertiseController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return "Hello";
        if (\Auth::user()->hasRole('Provider')) {
            $search_categories = unserialize(json_decode(\Auth::user()->provider->ppivot->fetch('category_id')[0]));
            $search_subcategories = unserialize(json_decode(\Auth::user()->provider->ppivot->fetch('subcategory_id')[0]));

            $categories = \DB::table('categories')->whereIn('id', $search_categories)->get(['id', 'name']);
            $subcategories = \DB::table('subcategories')->whereIn('id', $search_subcategories)->get(['id', 'name']);
            return view('Advertise.index', compact('categories', 'subcategories'));
        } else {
            return \Redirect::to('/plan/show');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        dd(\Input::get());
    }

    public function admin()
    {
        $advertisements = \Auth::user()->plans;
        return view('Advertise.admin', compact('advertisements'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param AdvertiseRequest $request
     * @return Response
     */
    public function store(AdvertiseRequest $request)
    {
        $plan = "";
        $plan_name = "";
        $clicks = "";
        $amount = "";

        switch (\Input::get("plan")) {
            case "1":
                $plan = 1;
                break;

            case "2":
                $plan = 2;
                break;

            case "3":
                $plan = 3;
                break;

            default:
                $plan = "0";
        }

        switch (\Input::get("plan_name")) {
            case "basic":
                $plan_name = "basic";
                break;

            case "most_popular":
                $plan_name = "most_popular";
                break;

            case "best_deal":
                $plan_name = "best_deal";
                break;

            default:
                $plan_name = null;
        }

        switch (\Input::get("clicks")) {
            case "20":
                $clicks = 20;
                break;

            case "50":
                $clicks = 50;
                break;

            case "150":
                $clicks = 150;
                break;

            default:
                $clicks = 0;
        }

        switch (\Input::get("amount")) {
            case "499":
                $amount = 499;
                break;

            case "944";
                $amount = 944;
                break;

            case "2499":
                $amount = 2499;
                break;

            default:
                $amount = 0;
        }

        $validator = \Validator::make($request->all(), $request->rules());
        if ($validator->fails()) {
            return \Redirect::to('/premium')->withErrors($validator);
        } else {
            \Stripe\Stripe::setApiKey('sk_test_3ljJklZR39vyq2JBaNUnB5Ir');
            $token = \Input::get('stripeToken');
            if (empty($token)) {
                return \Redirect::to('/');
            } else {
                $user = \Auth::user();
                if ($user->stripe_id == "" || is_null($user->stripe_id) || empty($user->stripe_id)) {
                    $customer = Customer::create(['description' => \Auth::user()->email . 'created account', 'source' => $token, 'email' => \Auth::user()->email]);
                    $user->stripe_id = $customer->id;
                    $user->card_id = $customer->default_source;
                    if ($user->save()) {
                        $customer = \Stripe\Customer::retrieve($customer->id);
                        try {
                            $charges = Charge::create(['amount' => $amount, 'customer' => $customer->id, 'currency' => 'usd', 'description' => \Auth::user()->email]);
                            $plan = new Plan();
                            $plan->user_id = \Auth::id();
                            $plan->user->stripe_active = 1;
                            $plan->user->save();
                            $plan->plan_type = $plan_name;
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
                                $categories = \Input::get('categories');
                                $subcategories = serialize(json_encode(\Input::get('subcategories')));
                                $title = \Input::get('title');
                                $description = \Input::get('description');
                                $images = [];
                                $upload_images = [];
                                $range = \Input::get('custom_range');
                                if (!isset($range) || $range == "" || empty($range)) {
                                    $range = \Input::get('range');
                                }

                                if (\Input::hasFile('images')) {
                                    foreach (\Input::file('images') as $image) {
                                        $my_image = $image->move(public_path('uploads/premium'), md5($image->getClientOriginalName() . \Auth::user()->email . rand(0, 2000)) . "." . $image->getClientOriginalExtension());
                                        $images[] = $my_image;
                                    }
                                    foreach ($images as $ximage) {
                                        Image::make(sprintf('uploads/premium/%s', $ximage->getFilename()))->resize(300, 200)->save();
                                        array_push($upload_images, $ximage->getFilename());
                                    }
                                    $image = implode(",", $upload_images);
                                } else {
                                    $image = "";
                                }

                                $plan_id = \DB::getPdo()->lastInsertId();

                                Plan::find($plan_id)->advertise()->updateOrCreate(['title' => $title], ['title' => $title, 'description' => $description, 'images' => $image, 'plan_id' => $plan_id, 'categories' => $categories, 'subcategories' => $subcategories, 'range' => $range]);

                                \Session::flash('message', 'Advertise Created Successfully now you can activate, deactivate it.');

                                \Mail::send('emails.payment', ['plan_name' => $plan_name, 'clicks' => $clicks, 'impressions' => $clicks, 'balance' => $amount], function ($message) {
                                    $message->to(\Auth::user()->email)->subject('Payment Success');
                                    Notification::create([
                                        'text' => 'Advertisement Created Successfully',
                                        'link' => '/advertise/admin',
                                        'from' => \Auth::id(),
                                        'user_id' => \Auth::id(),
                                    ]);
                                });

                                return \Redirect::to('/advertise/admin');
                            }
                        } catch (\Stripe\Error\Card $e) {
                            return \Response::make("Sorry : " . $e->getMessage(), 400);
                        }
                    }
                } elseif (is_null($user->card_id) || empty($user->card_id)) {
                    $customer = Customer::retrieve($user->stripe_id);
                    $ch = $customer->sources->create(['source' => $token]);
                    \Auth::user()->card_id = $ch->id;
                    \Auth::user()->last_four = $ch->last4;
                    \Auth::user()->save();
                } else {
                    if ($user->stripe_id != null || !empty($user->stripe_id) || $user->card_id != null || !empty($user->card_id)) {
                        $customer = \Stripe\Customer::retrieve($user->stripe_id);

                        try {
                            $charges = Charge::create(['amount' => $amount, 'customer' => $customer->id, 'currency' => 'usd', 'description' => \Auth::user()->email]);
                            $plan = new Plan();
                            $plan->user_id = \Auth::id();
                            $plan->user->stripe_active = 1;
                            $plan->user->save();
                            $plan->plan_type = $plan_name;
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
                                $categories = \Input::get('categories');
                                $subcategories = \Input::get('subcategories');
                                $title = \Input::get('title');
                                $description = \Input::get('description');
                                $images = [];
                                $upload_images = [];
                                $range = 'default';

                                if (\Input::hasFile('images')) {
                                    foreach (\Input::file('images') as $image) {
                                        $my_image = $image->move(public_path('uploads/premium'), md5($image->getClientOriginalName() . \Auth::user()->email . rand(0, 2000)) . "." . $image->getClientOriginalExtension());
                                        $images[] = $my_image;
                                    }
                                    foreach ($images as $ximage) {
                                        Image::make(sprintf('uploads/premium/%s', $ximage->getFilename()))->resize(300, 200)->save();
                                        array_push($upload_images, $ximage->getFilename());
                                    }
                                    $image = implode(",", $upload_images);
                                } else {
                                    $image = "";
                                }

                                $plan_id = \DB::getPdo()->lastInsertId();

                                Plan::find($plan_id)->advertise()->updateOrCreate(['title' => $title], ['title' => $title, 'description' => $description, 'images' => $image, 'plan_id' => $plan_id, 'categories' => $categories, 'subcategories' => serialize(json_encode($subcategories)), 'range' => $range]);

                                \Session::flash('message', 'Advertise Created Successfully now you can activate, deactivate it.');

                                \Mail::send('emails.payment', ['plan_name' => $plan_name, 'clicks' => $clicks, 'impressions' => $clicks, 'balance' => $amount], function ($message) {
                                    $message->to(\Auth::user()->email)->subject('Payment Success');
                                    Notification::create([
                                        'text' => 'Advertisement Created Successfully',
                                        'link' => '/advertise/admin',
                                        'from' => \Auth::id(),
                                        'user_id' => \Auth::id(),
                                    ]);
                                });

                                return \Redirect::to('/advertise/admin');
                            }
                        } catch (\Stripe\Error\Card $e) {
                            return \Response::make("Sorry : " . $e->getMessage(), 400);
                        }
                    }
                }
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function show($id)
    {

        //

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function edit($id, $plan)
    {
        if (\Auth::check()) {
            if (\Auth::user()->hasRole('Provider')) {
                $advertisement = Plan::whereUserId(\Auth::id())->whereId($plan)->with(['advertise' => function ($query) use ($id) {
                    return $query->where('id', '=', $id);
                },
                ])->first();

                $images = explode(",", $advertisement->advertise->images);

                $search_categories = unserialize(json_decode(\Auth::user()->provider->ppivot->fetch('category_id')[0]));
                $search_subcategories = unserialize(json_decode(\Auth::user()->provider->ppivot->fetch('subcategory_id')[0]));

                $categories = \DB::table('categories')->whereIn('id', $search_categories)->get(['id', 'name']);
                $subcategories = \DB::table('subcategories')->whereIn('id', $search_subcategories)->get(['id', 'name']);

                return view('Advertise.edit', compact('categories', 'subcategories', 'id', 'advertisement', 'images'));
            } else {
                return \Redirect::to('/plan/show');
            }
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int $id
     * @return Response
     */
    public function update($id)
    {
        $categories = \Input::get('categories');
        $subcategories = serialize(json_encode(\Input::get('subcategories')));
        $title = \Input::get('title');
        $description = \Input::get('description');
        $images = [];
        $upload_images = [];
        $range = \Input::get('custom_range');
        if (!isset($range) || $range == "" || empty($range)) {
            $range = \Input::get('range');
        }

        if (\Input::hasFile('images')) {
            foreach (\Input::file('images') as $image) {
                $my_image = $image->move(public_path('uploads/premium'), md5($image->getClientOriginalName() . \Auth::user()->email . rand(0, 2000)) . "." . $image->getClientOriginalExtension());
                $images[] = $my_image;
            }
            foreach ($images as $ximage) {
                Image::make(sprintf('uploads/premium/%s', $ximage->getFilename()))->resize(300, 200)->save();
                array_push($upload_images, $ximage->getFilename());
            }
            $image = implode(",", $upload_images);
        } else {
            $imgs = Adv_Management::wherePlanId($id)->get(['images']);
            $image = $imgs[0]->images;
        }

        Plan::find($id)->advertise()->update(['title' => $title, 'description' => $description, 'images' => $image, 'categories' => $categories, 'subcategories' => $subcategories, 'range' => $range]);

        if (Input::get('renew') === '1') {
            Plan::whereId($id)->update(['auto_renew' => 1]);
        } else {
            Plan::whereId($id)->update(['auto_renew' => 0]);
        }

        \Session::flash('message', 'Advertise Updated.');
        return \Redirect::to('/advertise/admin');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return Response
     */
    public function destroy($id)
    {

    }
}
