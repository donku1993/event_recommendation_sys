<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use View;

use App\Models\Helper;
use App\Http\Controllers\ControllerHelperTrait;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests, ControllerHelperTrait;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    	View::share('constant_array', Helper::AllConstantArray()['constant_array']);
        View::share('auth_status_array', $this->auth_status_array());
    }

    public function auth_status_array()
    {
        return [
            'is_login' => $this->isLogin(),
            'is_admin' => $this->isAdmin(),
            'is_manager' => $this->isManager()
        ];
    }
}
