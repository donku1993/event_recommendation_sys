<?php

namespace App\Http\Controllers;

use App\Models\Helper;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home');
    }

    public function eventInfo(){
        return view('event/info');
    }


    public function userInfo(){
        return  view('auth/info')->with([
            'location_array'=>Helper::getConstantArray('location'),
            'event_type_array' => Helper::getConstantArray('event_type'),
            'interest_skills_array' => Helper::getConstantArray('interest_skills'),
            'career_array' => Helper::getConstantArray('career'),
            'available_time_array' => Helper::getConstantArray('available_time'),
            'user_gender_array' => Helper::getConstantArray('user_gender'),

        ]);
    }

    public function eventCreate(){
        return  view('event/create')->with([
            'location_array'=>Helper::getConstantArray('location'),
            'event_type_array' => Helper::getConstantArray('event_type'),
            'interest_skills_array' => Helper::getConstantArray('interest_skills')
        ]);
    }
}
