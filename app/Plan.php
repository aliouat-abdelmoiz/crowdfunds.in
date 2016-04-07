<?php namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Plan
 *
 * @property integer $id
 * @property string $customer_id
 * @property integer $user_id
 * @property string $plan_type
 * @property float $plan_price
 * @property float $balance
 * @property boolean $status
 * @property boolean $auto_renew
 * @property float $click_cost
 * @property float $impression_cost
 * @property float $daily_budget
 * @property string $title
 * @property string $description
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \App\User $user
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Adv_Management[] $premium
 * @method static \Illuminate\Database\Query\Builder|\App\Plan whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Plan whereCustomerId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Plan whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Plan wherePlanType($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Plan wherePlanPrice($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Plan whereBalance($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Plan whereStatus($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Plan whereAutoRenew($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Plan whereClickCost($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Plan whereImpressionCost($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Plan whereDailyBudget($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Plan whereTitle($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Plan whereDescription($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Plan whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Plan whereUpdatedAt($value)
 * @property string $object
 * @property integer $last4
 * @property string $brand
 * @property string $funding
 * @property integer $exp_month
 * @property integer $exp_year
 * @property string $fingerprint
 * @property string $country
 * @method static \Illuminate\Database\Query\Builder|\App\Plan whereObject($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Plan whereLast4($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Plan whereBrand($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Plan whereFunding($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Plan whereExpMonth($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Plan whereExpYear($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Plan whereFingerprint($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Plan whereCountry($value)
 * @property boolean $active
 * @property string $charge_id
 * @property boolean $refunded
 * @property boolean $paid
 * @property float $amount
 * @property string $currency
 * @property string $balance_transaction
 * @property float $amount_refunded
 * @property string $invoice
 * @property-read \App\Adv_Management $advertise
 * @method static \Illuminate\Database\Query\Builder|\App\Plan whereActive($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Plan whereChargeId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Plan whereRefunded($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Plan wherePaid($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Plan whereAmount($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Plan whereCurrency($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Plan whereBalanceTransaction($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Plan whereAmountRefunded($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Plan whereInvoice($value)
 */
class Plan extends Model {

    public $fillable = ['user_id','plan_type', 'plan_price', 'active', 'balance', 'status', 'auto_renew', 'click_cost', 'impression_cost', 'object', 'charge_id', 'paid', 'amount', 'currency', 'balance_transaction', 'invoice'];

    public function user()
    {
        return $this->belongsTo('App\User');
	}

    public function advertise()
    {
        return $this->hasOne('App\Adv_Management');
    }

}
