<?php namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Subcategory
 *
 * @property-read \App\Category $category
 * @property integer $id
 * @property string $name
 * @property string $slug
 * @property string $alt
 * @property integer $category_id
 * @property string $status
 * @property string $image
 * @property string $tags
 * @property string $suggested_tags
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Subcategory whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Subcategory whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Subcategory whereSlug($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Subcategory whereAlt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Subcategory whereCategoryId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Subcategory whereStatus($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Subcategory whereImage($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Subcategory whereTags($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Subcategory whereSuggestedTags($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Subcategory whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Subcategory whereUpdatedAt($value)
 * @property string $title 
 * @method static \Illuminate\Database\Query\Builder|\App\Subcategory whereTitle($value)
 */
class Subcategory extends Model {

    public function category()
    {
        return $this->belongsTo('App\Category');
    }

}
