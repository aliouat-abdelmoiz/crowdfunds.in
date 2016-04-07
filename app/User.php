<?php namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Zizaco\Entrust\Traits\EntrustUserTrait;

/**
 * App\User
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Category[] $categories
 * @property-read \App\Userinformation $userinfo
 * @property integer $id
 * @property string $name
 * @property string $username
 * @property string $email
 * @property string $password
 * @property string $remember_token
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\User whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereUsername($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereEmail($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User wherePassword($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereRememberToken($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereUpdatedAt($value)
 * @property boolean $activate
 * @property string $confirm_code
 * @property string $provider_type
 * @method static \Illuminate\Database\Query\Builder|\App\User whereActivate($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereConfirmCode($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereProviderType($value)
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Role[] $roles
 * @property-read \App\Provider $provider
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Message[] $messages
 * @property boolean $disabled
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Project[] $projects
 * @method static \Illuminate\Database\Query\Builder|\App\User whereDisabled($value)
 * @property string $facebook_id
 * @property string $google_id
 * @property string $avatar
 * @property boolean $stripe_active
 * @property string $stripe_id
 * @property string $stripe_subscription
 * @property string $stripe_plan
 * @property string $last_four
 * @property \Carbon\Carbon $trial_ends_at
 * @property \Carbon\Carbon $subscription_ends_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Conversation[] $conversations
 * @method static \Illuminate\Database\Query\Builder|\App\User whereFacebookId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereGoogleId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereAvatar($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereStripeActive($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereStripeId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereStripeSubscription($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereStripePlan($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereLastFour($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereTrialEndsAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereSubscriptionEndsAt($value)
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Plan[] $plans
 * @property string $card_id
 * @method static \Illuminate\Database\Query\Builder|\App\User whereCardId($value)
 * @property integer $notifcation_count 
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Notification[] $Notifications 
 * @property-read \App\Subscribe $subscription 
 * @method static \Illuminate\Database\Query\Builder|\App\User whereNotifcationCount($value)
 */
class User extends Model implements AuthenticatableContract, CanResetPasswordContract {

    use EntrustUserTrait;
	use Authenticatable, CanResetPassword;
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['name', 'username', 'email', 'password', 'provider_type', 'confirm_code', 'facebook_id', 'google_id', 'avatar', 'activate'];

    protected $dates = ['trial_ends_at', 'subscription_ends_at'];

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = ['password', 'remember_token'];

    public function categories() {
        return $this->hasMany('App\Category');
    }

    public function userinfo() {
        return $this->hasOne('App\Userinformation');
    }

    public function roles(){
        return $this->belongsToMany('App\Role', 'role_user');
    }

    public function provider() {
        return $this->hasOne('App\Provider');
    }

    public function projects()
    {
        return $this->hasMany('App\Project');
    }

    public function messages()
    {
        return $this->hasMany('App\Message');
    }

    public function conversations()
    {
        return $this->belongsToMany('App\Conversation');
    }

    public static function getUsername($user)
    {
        return User::find($user)->name;
    }

    public function plans()
    {
        return $this->hasMany('App\Plan');
    }

    public static function getDistance($point1, $point2) {

        $radius      = 3958;      // Earth's radius (miles)
        $pi          = 3.1415926;
        $deg_per_rad = 57.29578;  // Number of degrees/radian (for conversion)

        $distance = ($radius * $pi * sqrt(
                ($point1['lat'] - $point2['lat'])
                * ($point1['lat'] - $point2['lat'])
                + cos($point1['lat'] / $deg_per_rad)  // Convert these to
                * cos($point2['lat'] / $deg_per_rad)  // radians for cos()
                * ($point1['long'] - $point2['long'])
                * ($point1['long'] - $point2['long'])
            ) / 180);

        $distance = round($distance,1);
        return $distance;  // Returned using the units used for $radius.
    }

    public function Notifications()
    {
        return $this->hasMany('App\Notification');
    }

    public function subscription()
    {
        return $this->hasOne('App\Subscribe');
    }

}
