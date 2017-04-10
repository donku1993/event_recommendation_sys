@extends('layouts.app')

@section('content')

    <div class="container">
        <div class="row">

            @foreach($participants as $participant)
                <div class="event-item col-md-2">
                    <div class="col-md-8">
                        <a href="/user/{{ $participant->id }}">
                            <img src="{{ $participant->iconPath }}" alt="" style="width: 210px; height: 210px">
                        </a>
                        <h4 class="member-name-list" style="text-align: center; width: 210px; color: #443f89;"><i class="user-groups glyphicon glyphicon-user"></i></i> {{ $participant->name }}</h4>

                        <select class="check-value form-control" name="grade">
                            @foreach ($constant_array['event_evaulation']['value'] as $key => $value)
                                <option value="{{ $key }}">{{ $value }}</option>
                            @endforeach
                        </select>

                    </div>

                </div>
                <div class="col-md-1"></div>
            @endforeach





        </div>


    </div>

@endsection

@section('script')
@endsection
