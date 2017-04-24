<?php

namespace App\Http\Controllers;

use File;
use Image;
use Auth;
use App\Models\User;
use App\Models\Event;
use App\Models\Group;
use App\Models\Helper;
use App\Models\Participant;

trait ControllerHelperTrait
{
	public function isLogin()
	{
		return (Auth::user()) ? true : false;
	}

	public function isAdmin()
	{
		if ($this->isLogin())
		{
			return (Auth::user()->type === Helper::getKeyByArrayNameAndValue('user_type', '系統管理員'));
		} else {
			return false;
		}
	}

	public function isManager()
	{
		if ($this->isLogin())
		{
			return (Auth::user()->type === Helper::getKeyByArrayNameAndValue('user_type', '組職管理員'));
		} else {
			return false;
		}
	}

	public function isSelf(User $user = null)
	{
		if ($this->isLogin() && $user)
		{
			return (Auth::user()->id === $user->id);
		} else {
			return false;
		}
	}

	public function isEventManager(Event $event = null)
	{
		if ($this->isLogin() && $event)
		{
			return (
					Auth::user()->type === Helper::getKeyByArrayNameAndValue('user_type', '組職管理員') &&
					Auth::user()->id === $event->organizer[0]->user_id
				);
		} else {
			return false;
		}
	}

	public function isMarkedEvent(Event $event = null)
	{
		if ($this->isLogin() && $event)
		{
			$auth_id = Auth::user()->id;
			$filtered = $event->markedUsers->filter(function ($value) use ($auth_id)
			{
				return $value->id == $auth_id;
			});

			return ($filtered->count() > 0) ? true : false;
		} else {
			return false;
		}
	}

	public function isParticipant(Event $event = null)
	{
		if ($this->isLogin() && $event)
		{
			$auth_id = Auth::user()->id;
			$participant_count = Participant::where('event_id', $event->id)->where('user_id', $auth_id)->count();

			return ($participant_count == 1) ? true : false;
		} else {
			return false;
		}
	}

	public function isFinishedEvent(Event $event = null)
	{
		if ($event)
		{
			return $event->status == Helper::getKeyByArrayNameAndValue('event_status', '活動已完結');
		} else {
			return false;
		}
	}

	public function isParticipantCanEvaluate(Event $event = null)
	{
		return ($this->isParticipant($event) && $this->isFinishedEvent($event));
	}

	public function isManagerCanEvaluate(Event $event = null)
	{
		return ($this->isEventManager($event) && $this->isFinishedEvent($event));
	}

	public function isGroupManager(Group $group = null)
	{
		if ($this->isLogin() && $group)
		{
			return (
					Auth::user()->type === Helper::getKeyByArrayNameAndValue('user_type', '組職管理員') &&
					Auth::user()->id === $group->user_id
				);
		} else {
			return false;
		}
	}

	public function isMarkedGroup(Group $group = null)
	{
		if ($this->isLogin() && $group)
		{
			$auth_id = Auth::user()->id;
			$filtered = $group->markedUsers->filter(function ($value) use ($auth_id)
			{
				return $value->id == $auth_id;
			});

			return ($filtered->count() > 0) ? true : false;
		} else {
			return false;
		}
	}

	public function isUnprocessGroupForm(Group $group = null)
	{
		if (is_null($group)) return false;
		
		return ($group->status == 0 || $group->status == 1) ? true : false;
	}

	public function imageUpload(string $foldername, $id, $image)
	{
		if (is_null($image))
		{
			return 'default.png';
		}

		$folder_path = storage_path('app/public/' . $foldername . '/' . $id . '/');
		$filename = str_random(5) . '.' . $image->extension();
		$full_path = $folder_path . $filename;

		if (!file_exists($folder_path))
		{
			mkdir($folder_path, 0777, true);
		} else
		{
			File::cleanDirectory($folder_path);
		}

		$image->move($folder_path, $filename);

		Image::make($full_path)->resize(200, 200)->save($full_path);

		return $id . '/' . $filename;
	}

	public function fileUpload(string $foldername, $id, $file)
	{
		if (is_null($file))
		{
			return '';
		}

		$folder_path = storage_path('app/public/' . $foldername . '/' . $id . '/');
		$filename = str_random(5) . '.' . $file->extension();
		$full_path = $folder_path . $filename;

		if (!file_exists($folder_path))
		{
			mkdir($folder_path, 0777, true);
		} else
		{
			File::cleanDirectory($folder_path);
		}

		$file->move($folder_path, $filename);

		return $id . '/' . $filename;
	}
}