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

    public function oldMsg(Request $request){
        $old_chats = Chat::where(function ($query) use ($request) {
            $query->where('sender_id', $request->sender_id)
                  ->where('receiver_id', $request->receiver_id);
        })
        ->orWhere(function ($query) use ($request) {
            $query->where('sender_id', $request->receiver_id)
                  ->where('receiver_id', $request->sender_id);
        })->with(['receiver','sender'])
        ->orderBy('created_at', 'asc') // Order by timestamp
        ->get();
    
        // Return messages as JSON response
        return response()->json($old_chats);
    } 

}
