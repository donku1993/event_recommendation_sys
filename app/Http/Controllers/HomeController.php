<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\RecommendationTrait;

class HomeController extends Controller
{
    use RecommendationTrait;

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
        $data = [
                    'newest_events' => $this->newestEvents(),
                    'most_popular_events' => $this->mostPopularEvents()
                ];

        return view('home', $data);
    }
}