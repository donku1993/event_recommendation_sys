<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Controllers\StatusGetterTrait;
class UserController extends Controller
{
    use StatusGetterTrait;
    public function status_array(User $user) {
        return [
            'is_login' => $this->isLogin(),
            'is_admin' => $this->isAdmin(),
            'is_self' => $this->isSelf($user),
        ];
    }
    protected function basicValidationArray() {
        return [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'career' => 'required|integer',
            'gender' => 'required|integer',
            'allow_email' => 'required',
            'phone' => 'required|min:8',
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
        $user = User::with(['markedGroup', 'markedEvent', 'groups', 'events'])->find($id);
        if ($user) {
            $status_array = $this->status_array($user);
            $data = [
                'user' => $user,
                'status_array' => $status_array
            ];
            return view('user.info', [
                'data'=>$data
                ]);
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
        if ($user) {
            $status_array = $this->status_array($user);
            $data = [
                'user' => $user,
                'status_array' => $status_array
            ];
            return view('user.edit', [
                'data'=>$data
            ]);
        }
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
                'self_introduction' => ''
            )
        );
        $this->validate($request, $validate_array);
        $data = $request->all();
        $user = User::find($id);
        if ($user && $this->isSelf($user)) {
            $data = Helper::JsonDataConverter($data, 'available_time', 'available_time');
            $data = Helper::JsonDataConverter($data, 'interest_skills', 'interest_skills');
            $data = Helper::JsonDataConverter($data, 'available_area', 'location');
            $user->fill([
                'name' => $data['name'],
                'email' => $data['email'],
                'career' => $data['career'],
                'gender' => $data['gender'],
                'phone' => $data['phone'],
                'address_location' => $data['address_location'],
                'self_introduction' => $data['self_introduction'],
                'allow_email' => ($data['allow_email'] === 'true') ? 1 : 0,
                'available_time' => $data['available_time'],
                'available_area' => $data['available_area'],
                'interest_skills' => $data['interest_skills']
            ]);
            $user->save();
            return [
                'message' => 'success',
                'user_id' => $user->id
            ];
        }
        return ['message' => 'need to be a user himself/herself or administrator.'];
    }
}