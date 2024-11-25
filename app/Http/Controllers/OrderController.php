<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Event;
use App\Events\OrderShipped;

class OrderController extends Controller
{
    public function oderShip(){
        event(new OrderShipped(Auth::id()));
    }  

}
