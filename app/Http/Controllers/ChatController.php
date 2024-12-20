<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Event;
use App\Events\OrderShipped;
use App\Events\TestData;
use App\Models\User;
use App\Models\Chat;
use Illuminate\Support\Facades\Redis;

class ChatController extends Controller
{
    public function userList(Request $request){
        $data['users'] = User::whereNot('id',Auth::user()->id)->get(); // Fetch all users
        return view('dashboard', $data);
    }    
   
    public function msg(Request $request){

        $Chat = new Chat;
        $Chat->msg = $request->msg;
        $Chat->receiver_id = $request->receiver_id;
        $Chat->sender_id = $request->sender_id;
        $Chat->save();

        event(new TestData($request->msg, Auth::user(),$request->receiver_id,$request->sender_id));
    } 

    public function oldMsg(Request $request)
    {
        $senderId = $request->sender_id;
        $receiverId = $request->receiver_id;

        // Generate a unique Redis key
        $cacheKey = "chats_sender_".$senderId ."_receiver_".$receiverId;

        $time_start = microtime(true);
        // Check if chats exist in Redis
        $cachedChats = Redis::get($cacheKey);
        $end_time = microtime(true);
        $execution_time = $end_time - $time_start;

        if (!empty($cachedChats)) {
            // Return hydrated chats
            return response()->json([
                'chats' => json_decode($cachedChats),
                'execution_time' => 'cache',
            ]);
        }else{
            $time_start = microtime(true);

            // Query the database if cache is not found
            $old_chats = Chat::where(function ($query) use ($senderId, $receiverId) {
                $query->where('sender_id', $senderId)
                    ->where('receiver_id', $receiverId);
            })
            ->orWhere(function ($query) use ($senderId, $receiverId) {
                $query->where('sender_id', $receiverId)
                    ->where('receiver_id', $senderId);
            })
            ->with(['receiver', 'sender'])
            ->orderBy('created_at', 'asc') // Order by timestamp
            ->get();

            $end_time = microtime(true);

            $execution_time = $end_time - $time_start;
    
            if(count($old_chats)>0){
                Redis::set($cacheKey, $old_chats->toJson());
            }
    
            // Return messages as JSON response
            return response()->json([
                'chats' => $old_chats,
                'execution_time' => 'db',
            ]);
        }

    } 

}
