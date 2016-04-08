<?php namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use JavaScript;

/**
 * App\Category
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Subcategory[] $subcategories
 * @property integer $id
 * @property string $name
 * @property string $slug
 * @property string $alt
 * @property string $title
 * @property string $description
 * @property string $content
 * @property string $tags
 * @property string $status
 * @property string $image
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Category whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Category whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Category whereSlug($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Category whereAlt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Category whereTitle($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Category whereDescription($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Category whereContent($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Category whereTags($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Category whereStatus($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Category whereImage($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Category whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Category whereUpdatedAt($value)
 * @property string $keywords
 * @method static \Illuminate\Database\Query\Builder|\App\Category whereKeywords($value)
 */
class Category extends Model
{

    public function subcategories()
    {

        return $this->hasMany('App\Subcategory');
    }

    public static function GetCategoriesName($id)
    {
        $categories = json_decode(unserialize($id));
        foreach ($categories as $category) {
            echo Categories::find($category)->name . ", ";
        }
    }

    public static function GetPrimaryUserPic($id)
    {
        if(\Auth::check()) {
            $pic = \DB::table('adv__managements')->join('plans', 'plans.id', '=',
                'adv__managements.plan_id')->where('categories', '=',
                $id)->where('plans.active', '=', 1)->where('user_id','<>', \Auth::id())->orderByRaw('RAND()')->select("*")->limit(1)->get();
        } else {
            $pic = \DB::table('adv__managements')->join('plans', 'plans.id', '=',
                'adv__managements.plan_id')->where('categories', '=',
                $id)->where('plans.active', '=', 1)->orderByRaw('RAND()')->select("*")->limit(1)->get();
        }

        if (count($pic) > 0) {
            $ip_info = \DB::table('ip')->select('*')->where('ip', '=', \Request::getClientIp())->where('plan_id', '=', $pic[0]->plan_id)->get();
            $provider = User::find($pic[0]->user_id)->provider->logo;

            if(count($ip_info) == 0 || $ip_info == null || empty($ip_info)) {
                $ip = \DB::table('ip')->insert([
                    'plan_id' => $pic[0]->plan_id,
                    'ip' => \Request::getClientIp(),
                    'created_at' => Carbon::now(),
                    'removed_at' => Carbon::now()->addHours(2)
                ]);

                $plan = Plan::find($pic[0]->plan_id);
                $balance = $plan->balance - 4;
                $plan->balance = $balance;
                $plan->save();

                $adv_impression = $plan->advertise->impression + 1;
                $plan->advertise->impression = $adv_impression;
                $plan->advertise->save();

            }

            return "uploads/users/logos/" . $provider;
        } else {
            return "not";
        }

    }


}