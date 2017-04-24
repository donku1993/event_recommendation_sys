Dear, {{$user->name}}<br>
<br>
Your group application form ({{ $group_form->name }}) is approved.<br>
<br>
Click the following link to check the detail of the result: <a href="{{ route('group_form.info', $group_form->id) }}">{{ route('group_form.info', $group_form->id) }}</a>.<br>
<br>
Best Regards,<br>
<br>
Volunteer Activity Platform <br>