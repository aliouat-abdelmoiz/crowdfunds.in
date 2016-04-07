<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Subscribe
 *
 * @property integer $id 
 * @property integer $user_id 
 * @property boolean $subscribed 
 * @property \Carbon\Carbon $created_at 
 * @property \Carbon\Carbon $updated_at 
 * @property-read \App\User $user 
 * @method static \Illuminate\Database\Query\Builder|\App\Subscribe whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Subscribe whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Subscribe whereSubscribed($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Subscribe whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Subscribe whereUpdatedAt($value)
 */
class Subscribe extends Model
{
    protected $table = "subscribe";
    protected $fillable = ['subscribed', 'user_id', 'created_at', 'updated_at'];
    public $timestamps = true;

    public function user()
    {
        return $this->belongsTo('App\User');
    }

}
