<?php
namespace App\Http\Controllers;

use DB;
use Auth;
use Illuminate\Http\Request;
use App\Models\Group;
use App\Models\Helper;

class GroupController extends Controller
{
    public function status_array(Group $group)
    {
        return [
                'is_login' => $this->isLogin(),
                'is_admin' => $this->isAdmin(),
                'is_marked_group' => $this->isMarkedGroup($group),
                'is_group_manager' => $this->isGroupManager($group),
            ];
    }

    protected function basicValidationArray()
    {
        return [
                'name' => 'required',
                'registered_id' => 'required',
                'registered_file' => 'required|file|mimetypes:application/pdf',
                'establishment_date' => 'required|date|before:now',
                'principal_name' => 'required',
                'email' => 'required|email',
                'phone' => 'required|min:8',
                'address' => 'required',
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
            'activity_area' => 'integer|nullable'
        ]);
        $keywords = $request->all();
        $groups = Group::isGroup()->search($keywords)->paginate(8);
        $data = [
                'groups' => $groups
            ];

        return view('group.list', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('group.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validate_array = array_merge(
                            $this->basicValidationArray(),
                            [
                                'icon_image' => 'image'
                            ]
                        );

        $this->validate($request, $validate_array);

        $data = $request->all();

        if ($this->isLogin())
        {
            $data = Helper::JsonDataConverter($data, 'activity_area', 'location');
            $group = Group::create([
                    'name' => $data['name'],
                    'registered_id' => $data['registered_id'],
                    'registered_file' => $this->fileUpload('group_application_form_file', $data['registered_file']),
                    'establishment_date' => $data['establishment_date'],
                    'principal_name' => $data['principal_name'],
                    'email' => $data['email'],
                    'phone' => $data['phone'],
                    'address' => $data['address']
                ]);

            if ($group)
            {
                $group->icon_image = $this->imageUpload('group_icon', $group->id, $request->input('icon_image', null));
                $group->save();

                return [
                    'message' => 'success',
                    'group_id' => $group->id
                ];
            }
        }

        return ['message' => 'need to log in'];
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $group = Group::isGroup()->with(['markedUsers', 'manager', 'events'])->find($id);

        if ($group)
        {
            $status_array = $this->status_array($group);
            $data = [
                'group' => $group,
                'status_array' => $status_array
            ];

            return view('group.info', $data);
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
        $group = Group::isGroup()->with(['markedUsers', 'manager'])->find($id);

        if ($group)
        {
            $status_array = $this->status_array($group);
            $data = [
                'group' => $group,
                'status_array' => $status_array
            ];

            return view('group.edit', $data);
        }
    }

    /**
     * User mark a group.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function mark($id)
    {
        $user = Auth::user();

        $group_mark_type = Helper::getConstantArray('users_groups_relation_type')['value']['marked'];

        if ($user)
        {
            $record = DB::table('users_groups_relation')
                            ->where('user_id', $user->id)
                            ->where('group_id', $id)
                            ->where('type', $group_mark_type)
                            ->first();

            if ($record)
            {
                DB::table('users_groups_relation')
                        ->where('user_id', $user->id)
                        ->where('group_id', $id)
                        ->where('type', $group_mark_type)
                        ->delete();
            } else {
                DB::table('users_groups_relation')
                        ->insert([
                                'user_id' => $user->id,
                                'group_id' => $id,
                                'type' => $group_mark_type
                            ]);
            }
        }

        return redirect()->route('group.info', $id);
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
                            [
                                'icon_image' => 'image'
                            ]
                        );

        $this->validate($request, $validate_array);

        $data = $request->all();
        $group = Group::isGroup()->with(['markedUsers', 'manager', 'events'])->find($id);

        if ($this->isGroupManager($group))
        {
            $data = Helper::JsonDataConverter($data, 'activity_area', 'location');
            $group->fill([
                    'principal_name' => $data['principal_name'],
                    'email' => $data['email'],
                    'phone' => $data['phone'],
                    'address' => $data['address'],
                    'icon_image' => $this->imageUpload('group_icon', $request->input('icon_image', null))
                ]);

            $group->save();

            return [
                'message' => 'success',
                'group_id' => $group->id
            ];
        }

        return ['message' => 'need to be the manager of the group'];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $group = Group::find($id);

        if ($group)
        {
            $group->show = 0;
            $group->save();
        
            return ['message' => 'success'];
        }

        return ['message' => 'there is no group with id = ' . $id];
    }
}