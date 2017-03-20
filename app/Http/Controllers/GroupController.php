<?php
namespace App\Http\Controllers;
use DB;
use Auth;
use Illuminate\Http\Request;
use App\Models\Group;
use App\Models\Helper;
use App\Http\Controllers\StatusGetterTrait;
class GroupController extends Controller
{
    use StatusGetterTrait;
    public function status_array(Group $group) {
        return [
            'is_login' => $this->isLogin(),
            'is_admin' => $this->isAdmin(),
            'is_marked_group' => $this->isMarkedGroup($group),
            'is_group_manager' => $this->isGroupManager($group),
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
        // delete this line to pass data to view
        dd($data);
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
        // delete this line to pass data to view
        dd($request);
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
        if ($group) {
            $status_array = $this->status_array($group);
            $data = [
                'group' => $group,
                'status_array' => $status_array
            ];
            // delete this line to pass data to view
            dd($data);
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
        if ($group) {
            $status_array = $this->status_array($group);
            $data = [
                'group' => $group,
                'status_array' => $status_array
            ];
            // delete this line to pass data to view
            dd($data);
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
        if ($user) {
            $record = DB::table('users_groups_relation')
                ->where('user_id', $user->id)
                ->where('group_id', $id)
                ->where('type', $group_mark_type)
                ->first();
            if ($record) {
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
        // delete this line to pass data to view
        dd([$request->all(), $id]);
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // delete this line to pass data to view
        dd($id);
    }
}