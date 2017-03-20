<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Models\Helper;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6|confirmed',
            'career' => 'required|integer',
            'gender' => 'required|integer',
            'allow_email' => 'required',
            'phone' => 'required|min:8',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        var_dump($data);
        $data = Helper::JsonDataConverter($data, 'available_time', 'available_time');
        $data = Helper::JsonDataConverter($data, 'interest_skills', 'interest_skills');
        $data = Helper::JsonDataConverter($data, 'available_area', 'location');

        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'career' => $data['career'],
            'gender' => $data['gender'],
            'phone' => $data['phone'],
            'allow_email' => ($data['allow_email'] === 'true') ? 1 : 0,
            'available_time' => $data['available_time'],
            'available_area' => $data['available_area'],
            'interest_skills' => $data['interest_skills']
        ]);
    }
}
