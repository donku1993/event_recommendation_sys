<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Group;
use App\Models\Helper;

class GroupFormController extends Controller
{
    protected function searchValidationArray()
    {
        return [
            'group_name' => 'string|nullable',
            'status' => 'integer|nullable'
        ];
    }

    public function status_array(Group $group = null)
    {
        return [
                'is_login' => $this->isLogin(),
                'is_admin' => $this->isAdmin(),
                'is_manager' => $this->isManager(),
                'is_unprocess_group_form' => $this->isUnprocessGroupForm($group),
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

        if ($this->isAdmin())
        {
            $keywords = $request->all();

            $group_forms = Group::searchForm($keywords)->orderBy('created_at', 'desc')->paginate(8);

            foreach ($this->searchValidationArray() as $key => $value) {
                $keywords[$key] = (!isset($keywords[$key]) || is_null($keywords[$key])) ? "" : $keywords[$key];
            }

            $status_array = $this->status_array();

            $data = [
                    'group_forms' => $group_forms,
                    'keywords' => (object)$keywords,
                    'status_array' => $status_array
                ];

            return view('group_form.list', $data);
        }

        return back()->withInput();
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

        $this->form_read($id);

        if ($group_form && ($this->isAdmin() || $this->isGroupCreatedBySelf($group_form)))
        {
            $status_array = $this->status_array($group_form);

            $data = [
                'group_form' => $group_form,
                'status_array' => $status_array
            ];

            return view('group_form.info', $data);
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
    public function approve($id, Request $request)
    {
        $data = $request->all();

        $group_form = Group::unprocessForm()->find($id);

        $result = Helper::getKeyByArrayNameAndValue('group_status', '已拒絕');

        $result = ($request->has('approve')) ? Helper::getKeyByArrayNameAndValue('group_status', '已批準') : $result;

        if ($this->isAdmin() && $group_form)
        {
            if ($result == Helper::getKeyByArrayNameAndValue('group_status', '已批準'))
            {
                $column_need_to_be_unique = [
                        'name' => $group_form->name,
                        'registered_id' => $group_form->registered_id,
                        'email' => $group_form->email
                    ];

                if (!Group::checkUnique($column_need_to_be_unique))
                {
                    $result = Helper::getKeyByArrayNameAndValue('group_status', '已拒絕');
                }
                else
                {
                    $manager = $group_form->manager;
                    $manager->fill([
                            'type' => Helper::getKeyByArrayNameAndValue('user_type', '組職管理員')
                        ]);

                    $manager->save();
                }
            }

            $group_form->fill([
                    'status' => $result,
                    'remark' => $request->input('remark', '')
                ]);

            $group_form->save();

            $this->fireGroupFormApprovedMailSendingJob($group_form->user_id, $group_form->id);

            return redirect()->route('group_form.info', $group_form->id);
        }

        return back()->withInput();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     */
    public function form_read($id)
    {
        $group_form = Group::find($id);

        if ($group_form->status == Helper::getKeyByArrayNameAndValue('group_status', '新提交'))
        {
            $group_form->fill([
                    'status' => Helper::getKeyByArrayNameAndValue('group_status', '審批中')
                ]);

            $group_form->save();
        }
    }
}