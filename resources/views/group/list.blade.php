@extends('layouts.app')

@section('content')
    @include('layouts.search-template')

    <div class="container">
        <div class="row">

        </div>
        @foreach($groups as $group)
            <div class="event-item col-md-2">
                <div class="col-md-6">
                    <a href="/group/{{ $group->id }}">
                        <img src="{{ $group->iconPath }}" alt="" style="width: 210px; height: 210px">
                    </a>
                    <h4 class="group-name-list" style="text-align: center; width: 210px; color: #368976;"><i class="user-groups glyphicon glyphicon-tower"></i></i> {{ $group->name }}</h4>

                </div>


            </div>
            <div class="col-md-1"></div>
        @endforeach

    </div>

@endsection

@section('script')
    <script type="text/javascript" src="/js/search.js"></script>
@endsection
