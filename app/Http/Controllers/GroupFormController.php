<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Group;
use App\Http\Controllers\StatusGetterTrait;

class GroupFormController extends Controller
{    
    use StatusGetterTrait;

    public function status_array(Group $group) {
        return [
                'is_login' => $this->isLogin(),
                'is_admin' => $this->isAdmin(),
            ];
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->validate($request, [
                'group_name' => 'string|nullable',
                'status' => 'integer|nullable'
            ]);

        $keywords = $request->all();
        $group_forms = Group::searchForm($keywords)->paginate(10);
        $data = [
                'group_forms' => $group_forms
            ];

        dd($data);

        return view('group_form.list', $data);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $group_form = Group::with(['markedUsers', 'applicant', 'events'])->find($id);

        if ($group_form) {
            $status_array = $this->status_array($group_form);

            $data = [
                'group_form' => $group_form,
                'status_array' => $status_array
            ];

            dd($data);

            return view('group_form.info', $data);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function approve(Request $request, $id)
    {
        dd([$request->all(), $id]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function reject(Request $request, $id)
    {
        dd([$request->all(), $id]);
    }
}
