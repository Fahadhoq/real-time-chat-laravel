<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\User;
use App\Models\Phone;
use App\Models\Role;
use App\Models\Post;
use App\Models\Images;
use App\Models\Tag;

class ORMController extends Controller
{
 
    public function hasOne(Request $request)
    {
       return $user_phones = User::with(['phone.simName','simName'])->get();

       return $user_phones = User::with('largestPriorityPhone')->get();
    }

    public function hasMany(Request $request)
    {
        //    return $user_phones = User::with('phones')->get();

        //has use for check user have phone number. give thos user which have at lest one phone number
        // return $user_phones = User::has('phones')->with('phones')->get();
    
        // $users = User::whereHas('phones', function($query){
        //                 $query->where('status','1');
        //             })->get();

        //get phone
        $user = User::find(1);
       return $user->phones()->where('status', 1)->get();

    }

    public function belongsTo(Request $request)
    {
       return $phone_user = Phone::with('user')->get();
    }

    public function manyToMany(Request $request)
    {
        //get user role
       return $user  = User::with('roles')->get();

       
        //get user role with out call roles in with
        // $user = User::find(2);
        // $user->roles;

        // foreach ($user->roles as $role) {
        //     echo $role->pivot;
        // }
    }

    public function oneToOne_Polymorphic(Request $request)
    {  
        // find post image
           $post = Post::with('image')->find(1);
           return $image = $post->image;

        //show image owner
        $image = Images::find(1);
       return $imageable = $image->imageable;
    }

    public function oneToMany_Polymorphic(Request $request)
    {  
        // find post image
          return $post = Post::with('images')->get();
    }

    public function manyToMany_Polymorphic(Request $request)
    {  
        // find post tags
        //   return $post = Post::with('tags')->get();

        //find tag videos
          return $video_tags = Tag::with('videos')->get();

    }
    

}
