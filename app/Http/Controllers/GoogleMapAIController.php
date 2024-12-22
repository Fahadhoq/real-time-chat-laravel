<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Spatie\Geocoder\Facades\Geocoder;

class GoogleMapAIController extends Controller
{
    // public function map(Request $request)
    // { 
    //     return view('map_ai.map');
    // }

    public function map(Request $request)
    { 
        $address = '1600 Amphitheatre Parkway, Mountain View, CA';
        $response = Http::get('https://maps.googleapis.com/maps/api/geocode/json', [
            'address' => $address,
            'key' => config('services.google_maps.api_key'),
        ]);

        if ($response->successful()) {
            $location = $response->json();
            dd($location);
        } else {
            dd('Failed to fetch location data.');
        }
    }

    
}
