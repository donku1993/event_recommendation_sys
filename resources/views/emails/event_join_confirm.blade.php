Dear, {{$user->name}}<br>
<br>
You have already joined the event: {{ $event->title }}.<br>
<br>
Click the following link to check the detail of the event: <a href="{{ route('event.info', $event->id) }}">{{ route('event.info', $event->id) }}</a>.<br>
<br>
Best Regards,<br>
<br>
Volunteer Activity Platform <br>