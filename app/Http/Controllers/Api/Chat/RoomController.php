<?php

namespace App\Http\Controllers\Api\Chat;

use App\Http\Controllers\Controller;
use App\Models\ChatMessage;
use App\Models\ChatRoom;
use App\Models\ChatroomUser;
use App\Models\User;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function index(Request $request)
    {
        $user = user($request);

        $rooms = ChatRoom::query()
            ->with([
                'users' => function ($query) use ($user) {
                    $query->where('user_id', $user->id);
                    $query->select(['chat_room_id', 'is_read']);
                }
                ])
            ->select('id', 'image', 'most_recent_user', 'most_recent_message', 'name', 'updated_at')
            // ->orderByDesc('updated_at')
            ->get();

        return response()->json($rooms);
    }

    public function create(Request $request)
    {
        $name  = $request->name;
        $users = $request->users;

        $imageName = 'default';
        
        $chatroom = ChatRoom::create([
            'name'  => $name,
            'image' => $imageName,
        ]);

        foreach ($users as $user) {
            $chatroom->users()->create([
                'user_id'   => $user['user_id'],
                'has_acces' => 1,
                'is_admin'  => $user['is_admin'],
            ]);
        }

        return response()->json(201);
    }

    public function addUser(Request $request)
    {
        $add = user($request);

        foreach ($request->users as $user) {
            $room = ChatroomUser::query()
                ->withTrashed()
                ->where('user_id', $user['user_id'])
                ->first();

            if (empty($room)) {
                $room->updateOrCreate(
                    [
                    'user_id' => $user['user_id'],
                ],
                    [
                    'chat_room_id' => $user['chat_room_id'],
                    'is_admin'     => 0,
                    'is_read'      => 1,
                ]
            );
            } elseif ($room->trashed() === true) {
                $room->restore();
            }
            $user_info = User::findOrFail($user['user_id']);

            $message = $user_info->user_name . ' has been added to the group';

            ChatMessage::create([
                'user_id'      => $add->id,
                'chat_room_id' => $user['chat_room_id'],
                'message'      => $message,
                'is_info'      => 1,
            ]);

            ChatRoom::where('id', $user['chat_room_id'])->update(['most_recent_message' => $message]);

            //event
        }

        return response()->json(200);
    }

    public function read(Request $request)
    {
        $user = user($request);

        $rooms = ChatroomUser::query()
            ->where('user_id', $user->id)
            ->where('chat_room_id', $request->chat_room_id)
            ->update(['is_read' => 1]);

        return response()->json($rooms);
    }

    public function leaveRoom(Request $request)
    {
        $user = user($request);

        $room = ChatroomUser::query()
            ->where('user_id', $user->id)
            ->where('chat_room_id', $request->chat_room_id)
            ->first();

        $room->delete();

        $message = $user->user_name . ' left the chat group';

        ChatMessage::create([
            'message'      => $message,
            'chat_room_id' => $request->chat_room_id,
            'user_id'      => $user->id,
            'is_info'      => 1,
        ]);

        ChatRoom::where('id', $request->chat_room_id)->update(['most_recent_message' => $message]);

        // event(new NewMessage($message,));

        return response()->json(200);
    }

    public function admin(Request $request)
    {
        ChatroomUser::query()
            ->where('user_id', $request->user_id)
            ->update([
                'is_admin' => 1
            ]);
            
        $user = User::findOrFail($request->user_id);

        $message = $user->user_name . ' has been promoted to admin';

        ChatMessage::create([
            'user_id'      => $request->user_id,
            'chat_room_id' => $request->chat_room_id,
            'message'      => $message
        ]);

        ChatRoom::where('id', $request->chat_room_id)->update(['most_recent_message' => $message]);

        //event

        return response()->json(200);
    }
}
