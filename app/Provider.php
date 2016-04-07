<?php namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Provider
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $subcategory_id
 * @property integer $category_id
 * @property string $name
 * @property string $range
 * @property string $city
 * @property string $state
 * @property string $country
 * @property string $phone
 * @property string $address
 * @property string $address2
 * @property integer $zip
 * @property float $latitude
 * @property float $longitude
 * @property string $email
 * @property string $website
 * @property string $testimonial
 * @property string $logo
 * @property string $note
 * @property boolean $license
 * @property boolean $insurance
 * @property boolean $handyman
 * @property string $status
 * @property string $reply
 * @property string $subscription
 * @property string $subscription2
 * @property string $subscription3
 * @property string $update_status
 * @property string $ban_email
 * @property integer $campaign_round
 * @property string $emails
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Provider whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Provider whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Provider whereSubcategoryId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Provider whereCategoryId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Provider whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Provider whereRange($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Provider whereCity($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Provider whereState($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Provider whereCountry($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Provider wherePhone($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Provider whereAddress($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Provider whereAddress2($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Provider whereZip($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Provider whereLatitude($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Provider whereLongitude($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Provider whereEmail($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Provider whereWebsite($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Provider whereTestimonial($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Provider whereLogo($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Provider whereNote($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Provider whereLicense($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Provider whereInsurance($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Provider whereHandyman($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Provider whereStatus($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Provider whereReply($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Provider whereSubscription($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Provider whereSubscription2($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Provider whereSubscription3($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Provider whereUpdateStatus($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Provider whereBanEmail($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Provider whereCampaignRound($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Provider whereEmails($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Provider whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Provider whereUpdatedAt($value)
 * @property string $youtube
 * @property-read \App\User $user
 * @method static \Illuminate\Database\Query\Builder|\App\Provider whereYoutube($value)
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\ProviderPivot[] $ppivot
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Project[] $projects
 * @property string $rating
 * @method static \Illuminate\Database\Query\Builder|\App\Provider whereRating($value)
 */
class Provider extends Model
{

    protected $fillable = ['user_id', 'name', 'range', 'category_id', 'subcategory_id', 'zip', 'youtube', 'website', 'testimonial', 'logo', 'note', 'licence', 'insurance', 'handyman', 'status', 'emails'];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function ppivot()
    {
        return $this->hasMany('App\ProviderPivot');
    }

    public function projects() {
        return $this->hasMany('App\Project');
    }

}