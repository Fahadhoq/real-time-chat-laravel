<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class CustomeGuardController extends Controller
{
    public function loginPage(){
        return view('auth.adminLogin');
    }
 
    public function login(Request $request)
    {
        
        // Validate the request
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Attempt to login using the 'admin' guard
        if (Auth::guard('admin')->attempt($credentials)) {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->back()->withErrors(['error' => 'Invalid credentials']);
    }

    public function user(Request $request)
    {
        
       return $admin = Auth::guard('admin')->user();

    }
    

}
