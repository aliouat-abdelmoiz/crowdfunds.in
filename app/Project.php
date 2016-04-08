<?php

namespace App;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Project
 *
 * @property integer $id
 * @property integer $categories_id
 * @property integer $subcategories_id
 * @property integer $user_id
 * @property string $provider_id
 * @property string $title
 * @property string $body
 * @property string $image
 * @property float $cost
 * @property string $est_time
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \App\Category $categories
 * @property-read \App\Subcategory $subcategories
 * @method static \Illuminate\Database\Query\Builder|\App\Project whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Project whereCategoriesId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Project whereSubcategoriesId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Project whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Project whereProviderId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Project whereTitle($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Project whereBody($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Project whereImage($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Project whereCost($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Project whereEstTime($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Project whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Project whereUpdatedAt($value)
 * @property-read \App\Provider $provider
 * @property string $feedback
 * @property boolean $ended
 * @property-read \App\User $user
 * @method static \Illuminate\Database\Query\Builder|\App\Project whereFeedback($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Project whereEnded($value)
 * @property boolean $license
 * @property boolean $insurance
 * @property boolean $handycap
 * @property string $range
 * @property boolean $premium
 * @property string $forward_date
 * @method static \Illuminate\Database\Query\Builder|\App\Project whereLicense($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Project whereInsurance($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Project whereHandycap($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Project whereRange($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Project wherePremium($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Project whereForwardDate($value)
 */
class Project extends Model {

    protected $fillable = ['categories_id', 'subcategories_id', 'user_id', 'provider_id', 'title', 'body', 'license', 'insurance', 'handycap', 'range', 'image', 'premium', 'forward_date'];

    protected $dates = ["created_at", "updated_at", "forward_date"];

    public function categories ( ){
        return $this->belongsTo('App\Category');
    }

    public function subcategories ( ){
        return $this->belongsTo('App\Subcategory');
    }

    public function provider()
    {
        return $this->belongsTo('App\Provider');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function getProjectsByProviderId($query)
    {
        return $query->where('provider_id', '=', \Auth::user()->provider->id);
    }

    public function getProviderAppliedProject($id)
    {
        return \DB::table('provider_client')->where('provider_id', '=', $id)->first();
    }

    public function test()
    {

    }

}