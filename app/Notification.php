<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Notification
 *
 * @property integer $id 
 * @property integer $user_id 
 * @property integer $from 
 * @property string $text 
 * @property string $link 
 * @property boolean $read 
 * @property \Carbon\Carbon $created_at 
 * @property \Carbon\Carbon $updated_at 
 * @property-read \App\User $user 
 * @method static \Illuminate\Database\Query\Builder|\App\Notification whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Notification whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Notification whereFrom($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Notification whereText($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Notification whereLink($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Notification whereRead($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Notification whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Notification whereUpdatedAt($value)
 */
class Notification extends Model
{
    protected $fillable = ['text', 'link', 'from', 'user_id'];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

}
