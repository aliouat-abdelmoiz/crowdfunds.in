<?php
/**
 * Created by PhpStorm.
 * User: mandeepgill
 * Date: 11/08/15
 * Time: 12:47 AM
 */

namespace App;


/**
 * App\Conversation
 *
 * @property integer $id
 * @property string $subject
 * @property boolean $opened
 * @property integer $user_id
 * @property integer $project_id
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Message[] $messages
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\User[] $user
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Conversation[] $conversation_user
 * @method static \Illuminate\Database\Query\Builder|\App\Conversation whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Conversation whereSubject($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Conversation whereOpened($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Conversation whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Conversation whereProjectId($value)
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Conversation whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Conversation whereUpdatedAt($value)
 */
class Conversation extends \Eloquent
{

    protected $table = "conversation";
    protected $fillable = ['subject', 'user_id', 'project_id'];
    public $timestamps = true;

    public $includes = array('App\User', 'App\Message');

    public function messages()
    {
        return $this->hasMany('App\Message');
    }

    public function user()
    {
        return $this->belongsToMany('App\User');
    }

    public function conversation_user()
    {
        return $this->belongsToMany('App\Conversation', 'conversation_user')->withPivot(['conversation_id', 'user_id', 'total_messages']);
    }

    public function getTotalMessages($conversation_id)
    {
        $total = \DB::select(' select `conversation`.*, `conversation_user`.`conversation_id` as `pivot_conversation_id`,
                              `conversation_user`.`user_id` as `pivot_user_id`, `conversation_user`.`total_messages` as `pivot_total_messages`
                              from `conversation` inner join `conversation_user` on `conversation`.`id` = `conversation_user`.`conversation_id`
                              WHERE conversation_user.user_id = ? and conversation_user.conversation_id = ? LIMIT 1', [\Auth::id(), $conversation_id]);
        return $total[0]->pivot_total_messages;
    }

}