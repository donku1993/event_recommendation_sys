<?php
namespace App\Http\Controllers;

use Auth;
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

    public function status_array()
    {
        return [
                'is_login' => $this->isLogin(),
                'is_admin' => $this->isAdmin(),
                'is_manager' => $this->isManager(),
            ];
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
                    'most_popular_events' => $this->mostPopularEvents(),
                    'recommend_events' => ($this->isLogin()) ? $this->recommendation_user_given(Auth::user()->id) : $this->randomJoinableEvents(4),
                ];

        return view('home', $data);
    }
}