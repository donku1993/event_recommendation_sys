<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $newest_event = Event::orderBy('created_at', 'desc')->take(4)->get();

        $data = [
                    'newest_event' => $newest_event,
                ];

        return view('home', $data);
        //return view('group_form.info');
    }
}