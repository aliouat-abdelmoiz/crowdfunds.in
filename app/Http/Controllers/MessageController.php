<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Message;
use App\Reply;
use App\User;

class MessageController extends Controller
{

    public function getConversation()
    {
        $conversation = User::find(\Auth::id())->conversations;
        return \Response::make($conversation);
    }

    public function getMessages()
    {
        $messages = Message::whereConversationId(\Input::get('id'))->get();
        return $messages;
    }

    public function index()
    {
        $user = User::find(\Auth::id());
        $conversations = $user->conversations()->withPivot('opened')->simplePaginate(10);
        return view('messages.index', compact('conversations', 'user'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function show ( $id )
    {
        $conversation_user = \DB::table('conversation_user')->where('conversation_id', '=', $id)->where('user_id', '=', \Auth::id())->get();
        $conv_id = $id;
        if($conversation_user != null) {
            $messages = Message::whereConversationId($id)->orderBy('created_at', 'DESC')->simplePaginate(10);
            return view('messages.show', compact('messages', 'conv_id'));
        } else {
            return view('messages.show');
        }
    }

    public function destroy($id)
    {
        Message::find($id)->delete();
        return \Redirect::back();
    }

}
