<?php

namespace App\Http\Controllers;

use File;
use Image;
use Auth;
use App\Models\User;
use App\Models\Event;
use App\Models\Group;
use App\Models\Helper;

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
		}

		$image->move($folder_path, $filename);

		Image::make($full_path)->resize(200, 200)->save($full_path);

		return $id . '/' . $filename;
	}

	public function fileUpload(string $foldername, $id, $file)
	{
		if (is_null($image))
		{
			return '';
		}

		$folder_path = storage_path('app/public/' . $foldername . '/' . $id . '/');
		$filename = str_random(5) . '.' . $file->extension();
		$full_path = $folder_path . $filename;

		if (!file_exists($folder_path))
		{
			mkdir($folder_path, 0777, true);
		}

		$file->move($folder_path, $filename);

		return $id . '/' . $filename;
	}
}