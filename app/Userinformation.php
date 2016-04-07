<?php
/**
 * Created by PhpStorm.
 * User: mandeepgill
 * Date: 22/04/15
 * Time: 1:00 PM
 */

namespace App;
use Illuminate\Database\Eloquent\Model as Model;

/**
 * App\Userinformation
 *
 * @property-read \users $user
 * @property integer $id
 * @property string $country
 * @property integer $zip_code
 * @property float $latitude
 * @property float $longitude
 * @property string $image
 * @property string $status
 * @property string $screen_name
 * @property string $city
 * @property string $current_place
 * @property string $state
 * @property string $phone
 * @property string $address
 * @property string $address2
 * @property string $profile_type
 * @property string $reply_message
 * @property integer $user_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Userinformation whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Userinformation whereCountry($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Userinformation whereZipCode($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Userinformation whereLatitude($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Userinformation whereLongitude($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Userinformation whereImage($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Userinformation whereStatus($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Userinformation whereScreenName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Userinformation whereCity($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Userinformation whereCurrentPlace($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Userinformation whereState($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Userinformation wherePhone($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Userinformation whereAddress($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Userinformation whereAddress2($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Userinformation whereProfileType($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Userinformation whereReplyMessage($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Userinformation whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Userinformation whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Userinformation whereUpdatedAt($value)
 * @property integer $marker_id
 * @method static \Illuminate\Database\Query\Builder|\App\Userinformation whereMarkerId($value)
 */
class Userinformation extends Model {

    protected $table = "userinformation";

    protected $fillable = ['country', 'zip_code', 'latitude', 'longitude', 'image', 'status', 'screen_name', 'city','current_place', 'state', 'phone', 'address', 'address2', 'profile_type', 'reply_message', 'user_id'];

    public function user() {
        return $this->belongsTo('App\User');
    }
}