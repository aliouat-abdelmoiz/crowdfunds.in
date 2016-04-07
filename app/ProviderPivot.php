<?php namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\ProviderPivot
 *
 * @property integer $id
 * @property string $category_id
 * @property string $subcategory_id
 * @property integer $provider_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \App\Provider $provider
 * @method static \Illuminate\Database\Query\Builder|\App\ProviderPivot whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ProviderPivot whereCategoryId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ProviderPivot whereSubcategoryId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ProviderPivot whereProviderId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ProviderPivot whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ProviderPivot whereUpdatedAt($value)
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Projects[] $projects
 */
class ProviderPivot extends Model {

    protected $table = 'provider_cat_subcat';
    protected $fillable = ['category_id', 'subcategory_id', 'provider_id', 'created_at', 'updated_at'];

	public function provider() {
        return $this->belongsTo('App\Provider');
    }

    public function projects()
    {
        return $this->hasMany('App\Projects');
    }

}