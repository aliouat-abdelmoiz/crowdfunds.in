<?php
/**
 * Created by PhpStorm.
 * User: mandeepgill
 * Date: 10/08/15
 * Time: 8:21 PM
 */

namespace App;


/**
 * App\Message
 *
 * @property integer $id
 * @property integer $conversation_id
 * @property integer $user_id
 * @property string $content
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \App\User $user
 * @property-read \App\Conversation $conversation
 * @method static \Illuminate\Database\Query\Builder|\App\Message whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Message whereConversationId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Message whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Message whereContent($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Message whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Message whereUpdatedAt($value)
 * @property integer $to
 * @property boolean $opened
 * @method static \Illuminate\Database\Query\Builder|\App\Message whereTo($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Message whereOpened($value)
 */
class Message extends \Eloquent
{
    protected $fillable = ['conversation_id', 'user_id', 'message_to', 'content'];

    public $includes = array('App\User', 'App\Conversation');

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function conversation()
    {
        return $this->belongsTo('App\Conversation');
    }

}
