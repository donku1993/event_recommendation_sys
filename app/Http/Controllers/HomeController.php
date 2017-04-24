<?php
namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use App\Http\Controllers\RecommendationTrait;

class HomeController extends Controller
{
    use RecommendationTrait;

    protected function searchValidationArray()
    {
        return [
                'time_from' => 'date|nullable',
                'time_to' => 'date|nullable',
                'event_name' => 'string|nullable',
                'type' => 'integer|nullable',
                'location' => 'integer|nullable'
            ];
    }

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
        $status_array = $this->status_array();

        $data = [
                    'newest_events' => $this->newestEvents(4),
                    'most_popular_events' => $this->mostPopularEvents(4),
                    'recommend_events' => ($this->isLogin()) ? $this->recommendation_user_given(Auth::user()->id) : $this->randomJoinableEvents(4),
                    'status_array' => $status_array
        ];

        return view('home', $data);
    }

    public function newest_events(Request $request)
    {
        $this->validate($request, $this->searchValidationArray());

        $keywords = $request->all();
        $events = $this->newestEvents();
        $events = new Paginator($events, 8);

        foreach ($this->searchValidationArray() as $key => $value) {
            $keywords[$key] = (!isset($keywords[$key]) || is_null($keywords[$key])) ? "" : $keywords[$key];
        }

        $status_array = $this->status_array();

        $data = [
                'events' => $events,
                'keywords' => (object)$keywords,
                'status_array' => $status_array
        ];

        return view('event.list', $data);
    }

    public function most_popular_events(Request $request)
    {
        $this->validate($request, $this->searchValidationArray());

        $keywords = $request->all();
        $events = $this->mostPopularEvents();
        $events = new Paginator($events, 8);

        foreach ($this->searchValidationArray() as $key => $value) {
            $keywords[$key] = (!isset($keywords[$key]) || is_null($keywords[$key])) ? "" : $keywords[$key];
        }

        $status_array = $this->status_array();

        $data = [
                'events' => $events,
                'keywords' => (object)$keywords,
                'status_array' => $status_array
            ];

        return view('event.list', $data);
    }

    public function recommend_events(Request $request)
    {
        $this->validate($request, $this->searchValidationArray());

        $keywords = $request->all();
        $events = ($this->isLogin()) ? $this->sort_by_recommendation_user_given(Auth::user()->id) : $this->randomJoinableEvents(0);
        $events = new Paginator($events, 8);

        foreach ($this->searchValidationArray() as $key => $value) {
            $keywords[$key] = (!isset($keywords[$key]) || is_null($keywords[$key])) ? "" : $keywords[$key];
        }

        $status_array = $this->status_array();

        $data = [
                'events' => $events,
                'keywords' => (object)$keywords,
                'status_array' => $status_array
        ];

        return view('event.list', $data);
    }
}