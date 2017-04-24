<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Helper;
use App\Models\User;

class UserController extends Controller
{
    public function status_array(User $user)
    {
        return [    
                'is_login' => $this->isLogin(),
                'is_admin' => $this->isAdmin(),
                'is_manager' => $this->isManager(),
                'is_self' => $this->isSelf($user),
            ];
    }

    protected function basicValidationArray()
    {
        return [
                'name' => 'required|max:255',
                'email' => 'required|email|max:255',
                'career' => 'required|integer',
                'gender' => 'required|integer',
                'allow_email' => 'required',
                'phone' => 'required|min:8'
            ];
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function show($id)
    {
        $user = User::with(['markedGroup', 'markedEvent', 'groups', 'history_events', 'joined_not_begin_events'])->find($id);

        if ($user)
        {
            $status_array = $this->status_array($user);
            $data = [
                    'user' => $user,
                    'status_array' => $status_array
                ];

            return view('user.info', $data);
        }
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::find($id);

        if ($user)
        {
            $status_array = $this->status_array($user);
            $data = [

                    'user' => $user,
                    'status_array' => $status_array
                ];

            return view('user.edit', $data);
        }

        return back()->withInput();
    }

    public function infoEvent($id)
    {
        $user = User::with(['markedGroup', 'markedEvent', 'groups', 'history_events', 'joined_not_begin_events'])->find($id);

        if ($user)
        {
            $status_array = $this->status_array($user);
            $data = [
                'user' => $user,
                'status_array' => $status_array
            ];

            return view('user.events', $data);
        }

        return back()->withInput();
    }

    public function infoGroup($id)
    {
        $user = User::with(['markedGroup', 'markedEvent', 'groups', 'history_events', 'joined_not_begin_events'])->find($id);

        if ($user)
        {
            $status_array = $this->status_array($user);
            $data = [
                'user' => $user,
                'status_array' => $status_array
            ];

            return view('user.groups', $data);
        }

        return back()->withInput();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validate_array = array_merge(
                                $this->basicValidationArray(),
                                array(
                                    'address_location' => 'integer',
                                    'year_of_volunteer' => 'integer',
                                    'self_introduction' => '',
                                )
                            );

        $this->validate($request, $validate_array);

        $data = $request->all();
        $user = User::find($id);

        if ($user && $this->isSelf($user)
            || $this->isAdmin())
        {
            $data = Helper::JsonDataConverter($data, 'available_time', 'available_time');
            $data = Helper::JsonDataConverter($data, 'available_area', 'location');
            $data = Helper::JsonDataConverter($data, 'interest_skills', 'interest_skills');
            $user->fill([
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'career' => $data['career'],
                    'gender' => $data['gender'],
                    'phone' => $data['phone'],
                    'year_of_volunteer' => $request->input('year_of_volunteer', $user->year_of_volunteer),
                    'address_location' => $request->input('address_location', $user->address_location),
                    'self_introduction' => $request->input('self_introduction', $user->self_introduction),
                    'allow_email' => ($data['allow_email'] === 'true') ? 1 : 0,
                    'available_time' => $data['available_time'],
                    'available_area' => $data['available_area'],
                    'interest_skills' => $data['interest_skills'],
                ]);

            $user->save();
        }

        return back()->withInput();
    }

    /**
     * Save the uploaded image and update the column resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function icon_update(Request $request, $id)
    {
        $validate_array = ['icon_image' => 'image'];

        $this->validate($request, $validate_array);

        $user = User::find($id);

        if ($user && $this->isSelf($user)
            || $this->isAdmin())
        {
            $user->fill([
                    'icon_image' => $this->imageUpload('user_icon', $user->id, $request->file('icon_image', null)),
                ]);

            $user->save();
        }

        return back()->withInput();
    }
}