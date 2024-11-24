<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;

class GoogleAuthController extends Controller
{
    public function redirect(){
        return Socialite::driver('google')->redirect();
    }

     // Handle Google callback
     public function callbackGoogle()
     {
         try {
             $googleUser = Socialite::driver('google')->stateless()->user();
 
             // Check if the user exists
             $user = User::where('email', $googleUser->getEmail())->first();
 
             if (!$user) {
                 // Create a new user 
                //  $user = User::create([
                //      'name' => $googleUser->getName(),
                //      'email' => $googleUser->getEmail(),
                //      'google_id' => $googleUser->getId(),
                //      'password' => bcrypt('12345678'), // Optional, for fallback
                //  ]);
                // Auth::login($user);
                // return redirect('/dashboard')->with('success', 'Logged in successfully!');

                return redirect('/login')->with('error', 'User Not Found.');
             }else{
                 // Log the user in
                 Auth::login($user);
     
                 return redirect('/dashboard')->with('success', 'Logged in successfully!');
             }
 
         } catch (\Exception $e) {
             return redirect('/login')->with('error', 'Unable to authenticate.');
         }
    }

}
