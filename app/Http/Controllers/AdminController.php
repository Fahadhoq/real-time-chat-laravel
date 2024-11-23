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

class AdminController extends Controller
{
 
    public function dashboard()
    {
        return view('adminDashboard');
    }

}
