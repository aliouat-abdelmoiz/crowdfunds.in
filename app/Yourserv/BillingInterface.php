<?php
/**
 * Created by PhpStorm.
 * User: mandeepgill
 * Date: 31/12/15
 * Time: 2:25 PM
 */

namespace App\Yourserv;


interface BillingInterface
{
    public function charge(array $data);
    public function createCustomer(array $data);
}