<?php

namespace App\Http\Controllers\Api\Chat;

use App\Events\NewMessage;
use App\Events\NewNotifyMessage;
use App\Http\Controllers\Controller;
use App\Models\ChatMessage;
use App\Models\ChatRoom;
use App\Models\ChatroomUser;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function index($id)
    {
        $messages = ChatMessage::query()
            ->with('user')
            ->where('chat_room_id', $id)
            ->orderByDesc('created_at')
            ->paginate(30);

        return response()->json($messages);
    }
    
    public function create(Request $request)
    {
        $user = user($request);

        $room_id = $request->chat_room_id;
        $message = $request->message;

        $new = ChatMessage::create([
            'chat_room_id' => $room_id,
            'user_id'      => $user->id,
            'message'      => $message,
        ]);

        $room_users = ChatRoom::query()
            ->with('users.user')
            ->where('id', $room_id)
            ->first();

        $room_users->update(['most_recent_message' => $message, 'most_recent_user' => $user->user_name]);

        broadcast(new NewMessage($room_id, $user, $message));

        foreach ($room_users->users as $user_room) {
            ChatroomUser::where('id', $user_room->id)->update([ 'is_read' => 0]);
            
            
            
            broadcast(new NewNotifyMessage($user_room->user, $message, $room_users, $user));
        }

        return response()->json(201);
    }
}
