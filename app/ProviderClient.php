<?php
/**
 * Created by PhpStorm.
 * User: mandeepgill
 * Date: 25/10/15
 * Time: 11:54 PM
 */

namespace App;


use Illuminate\Database\Eloquent\Model;

/**
 * App\ProviderClient
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $provider_id
 * @property integer $project_id
 * @property boolean $applied
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\ProviderClient whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ProviderClient whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ProviderClient whereProviderId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ProviderClient whereProjectId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ProviderClient whereApplied($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ProviderClient whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ProviderClient whereUpdatedAt($value)
 */
class ProviderClient extends Model
{

    protected $table = "provider_client";
    protected $fillable = ["user_id", "project_id", "provider_id", "applied"];

}