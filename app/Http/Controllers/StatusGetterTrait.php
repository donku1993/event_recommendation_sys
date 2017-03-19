<?php

namespace App\Http\Controllers;

use Auth;
use App\Models\User;
use App\Models\Event;
use App\Models\Group;
use App\Models\Helper;

trait StatusGetterTrait
{
	public function isLogin() {
		return (Auth::user()) ? true : false;
	}

	public function isAdmin() {
		if ($this->isLogin()) {
			return (Auth::user()->type === array_search('系統管理員', Helper::getConstantArray('user_type')['value']));
		} else {
			return false;
		}
	}

	public function isManager() {
		if ($this->isLogin()) {
			return (Auth::user()->type === array_search('組職管理員', Helper::getConstantArray('user_type')['value']));
		} else {
			return false;
		}
	}

	public function isSelf(User $user = null) {
		if ($this->isLogin() && $user) {
			return (Auth::user()->id === $user->id);
		} else {
			return false;
		}
	}

	public function isEventManager(Event $event = null) {
		if ($this->isLogin() && $event) {
			return (
					Auth::user()->type === array_search('組職管理員', Helper::getConstantArray('user_type')['value']) &&
					Auth::user()->id === $event->organizer[0]->user_id
				);
		} else {
			return false;
		}
	}

	public function isMarkedEvent(Event $event = null) {
		if ($this->isLogin() && $event) {
			$auth_id = Auth::user()->id;
			$filtered = $event->markedUsers->filter(function ($value) use ($auth_id) {
				return $value->id == $auth_id;
			});

			return ($filtered->count() > 0) ? true : false;
		} else {
			return false;
		}
	}

	public function isGroupManager(Group $group = null) {
		if ($this->isLogin() && $group) {
			return (
					Auth::user()->type === array_search('組職管理員', Helper::getConstantArray('user_type')['value']) &&
					Auth::user()->id === $group->user_id
				);
		} else {
			return false;
		}
	}

	public function isMarkedGroup(Group $group = null) {
		if ($this->isLogin() && $group) {
			$auth_id = Auth::user()->id;
			$filtered = $group->markedUsers->filter(function ($value) use ($auth_id) {
				return $value->id == $auth_id;
			});

			return ($filtered->count() > 0) ? true : false;
		} else {
			return false;
		}
	}
}