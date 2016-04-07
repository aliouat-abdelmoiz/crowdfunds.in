<?php namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Adv_Management
 *
 * @property integer $id
 * @property integer $plan_id
 * @property integer $total_click
 * @property integer $total_impression
 * @property string $range
 * @property string $categories
 * @property string $subcategories
 * @property string $images
 * @property string $role
 * @property string $title
 * @property string $description
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \App\Plan $plan
 * @method static \Illuminate\Database\Query\Builder|\App\Adv_Management whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Adv_Management wherePlanId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Adv_Management whereTotalClick($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Adv_Management whereTotalImpression($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Adv_Management whereRange($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Adv_Management whereCategories($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Adv_Management whereSubcategories($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Adv_Management whereImages($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Adv_Management whereRole($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Adv_Management whereTitle($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Adv_Management whereDescription($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Adv_Management whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Adv_Management whereUpdatedAt($value)
 */
class Adv_Management extends Model {

    public $fillable = ['plan_id', 'title', 'description', 'images', 'categories', 'subcategories', 'range', 'role'];
    public function plan()
    {
        return $this->belongsTo('App\Plan');
	}

}
