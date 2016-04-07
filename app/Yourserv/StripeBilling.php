<?php
/**
 * Created by PhpStorm.
 * User: mandeepgill
 * Date: 31/12/15
 * Time: 2:26 PM
 */

namespace App\Yourserv;

use Cartalyst\Stripe\Exception\CardErrorException;
use Stripe\Charge;
use Stripe\Customer;
use Stripe\Stripe;

class StripeBilling implements BillingInterface
{
    public function __construct()
    {
        $key = \Config::get('stripe.secret');
        Stripe::setApiKey($key);
    }

    public function charge(array $data)
    {
        try {
            return Charge::create([
                'amount' => 100,
                'currency' => 'usd',
                'description' => $data['email'],
                'card' => $data['token']
            ]);
        } catch(CardErrorException $e) {
            dd('Card declined');
        }
    }

    public function createCustomer(array $data)
    {
        try {
            return Customer::create([
                'description' => $data['description'],
                'source' => $data['token'],
                'email' => $data['email']
            ]);
        } catch(CardErrorException $e) {
            return $e->getMessage();
        }
    }
}