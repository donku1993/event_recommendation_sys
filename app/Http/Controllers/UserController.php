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

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::with(['markedGroup', 'markedEvent', 'events', 'groups'])->find($id);

        if ($user) {
            $status_array = $this->status_array($user);

            $data = [
                    'user' => $user,
                    'status_array' => $status_array
                ];

            dd($data);

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

        if ($user) {
            $status_array = $this->status_array($user);

            $data = [
                    'user' => $user,
                    'status_array' => $status_array
                ]);

            dd($data);

            return view('user.eidt', $data);
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
        dd([$request->all(), $id]);
    }
}
