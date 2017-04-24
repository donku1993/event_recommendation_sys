<?php
namespace App\Http\Controllers;

use DB;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Group;
use App\Models\Helper;

class GroupController extends Controller
{
    public function status_array(Group $group = null)
    {
        return [
                'is_login' => $this->isLogin(),
                'is_admin' => $this->isAdmin(),
                'is_manager' => $this->isManager(),
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

    protected function searchValidationArray()
    {
        return [
                'group_name' => 'string|nullable',
                'activity_area' => 'integer|nullable'
            ];
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->validate($request, $this->searchValidationArray());
        $keywords = $request->all();

        $groups = Group::isGroup()->search($keywords)->paginate(8);

        foreach ($this->searchValidationArray() as $key => $value) {
            $keywords[$key] = (!isset($keywords[$key]) || is_null($keywords[$key])) ? "" : $keywords[$key];
        }

        $status_array = $this->status_array();

        $data = [
                'groups' => $groups,
                'keywords' => (object)$keywords,
                'status_array' => $status_array
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
        $status_array = $this->status_array();

        if ($this->isLogin())
        {
            $data = [
                'status_array' => $status_array
            ];

            return view('group.create', $data);
        }

        return back()->withInput();
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

        if ($this->isLogin() && Group::checkUnique($data))
        {
            $user = Auth::user();

            $data = Helper::JsonDataConverter($data, 'activity_area', 'location');
            $group = Group::create([
                    'user_id' => $user->id,
                    'name' => $data['name'],
                    'registered_id' => $data['registered_id'],
                    'establishment_date' => new Carbon($data['establishment_date']),
                    'activity_area' => $data['activity_area'],
                    'principal_name' => $data['principal_name'],
                    'email' => $data['email'],
                    'phone' => $data['phone'],
                    'address' => $data['address'],
                    'introduction' => $data['introduction']
                ]);

            if ($group)
            {
                $group->icon_image = $this->imageUpload('group_icon', $group->id, $request->file('icon_image', null));
                $group->registered_file = $this->fileUpload('group_application_form_file', $group->id, $data['registered_file']);
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

        $group = Group::find($id);

        if ($group)
        {
            return redirect()->route('group_form.info', $group->id);
        }

        return back()->withInput();
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

        if ($group && $this->isGroupManager($group)
            || $this->isAdmin())
        {
            $status_array = $this->status_array($group);
            $data = [
                'group' => $group,
                'status_array' => $status_array
            ];

            return view('group.edit', $data);
        }

        return back()->withInput();
    }

    /**
     * User mark a group.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function mark($id)
    {
        $group_mark_type = Helper::getConstantArray('users_groups_relation_type')['value']['marked'];

        if ($this->isLogin())
        {
            $user = Auth::user();

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
        $validate_array = $this->basicValidationArray();

        unset($validate_array['registered_id']);
        unset($validate_array['registered_file']);

        $this->validate($request, $validate_array);

        $data = $request->all();
        $group = Group::isGroup()->with(['markedUsers', 'manager', 'events'])->find($id);

        if ($this->isGroupManager($group)
            || $this->isAdmin())
        {
            if (!Group::checkUnique($data))
            {
                return ['message' => 'email or group name has already exist'];
            }

            $data = Helper::JsonDataConverter($data, 'activity_area', 'location');
            $group->fill([
                    'name' => $data['name'],
                    'principal_name' => $data['principal_name'],
                    'email' => $data['email'],
                    'phone' => $data['phone'],
                    'address' => $data['address'],
                    'introduction' => $data['introduction']
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

        $group = Group::isGroup()->with(['markedUsers', 'manager', 'events'])->find($id);

        if ($this->isGroupManager($group)
            || $this->isAdmin())
        {
            $group->fill([
                    'icon_image' => $this->imageUpload('group_icon', $group->id, $request->file('icon_image', null)),
                ]);

            $group->save();
        }

        return back()->withInput();
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

        if ($this->isGroupManager($group)
            || $this->isAdmin())
        {
            $group->show = 0;
            $group->save();
        }

        return back()->withInput();
    }

    public function download_registered_file($id)
    {
        $group = Group::find($id);

        if ($group && ($this->isAdmin() || $this->isGroupManager($group)) && $group->registered_file != '')
        {
            return response()->download($group->downloadRegisteredFile);
        }

        return back()->withInput();
    }
}