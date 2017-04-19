@extends('layouts.app')

@section('content')
    @include('layouts.search-template') {{--need to edit later--}}
    <div class="container">
        <div class="row">
            <div class="event-item col-md-12" style="margin-bottom: 10px">
                @foreach($group_forms as $group_form)
                        <div class="col-md-3">
                            <a href="/group_form/{{ $group_form->id }}">
                                <img src="{{ $group_form->iconPath }}" alt="" style="width: 210px; height: 210px">
                            </a>
                            <h4 class="group-name-list" style="text-align: center; width: 210px; color: #368976;"><i class="user-groups glyphicon glyphicon-tower"></i></i> {{ $group_form->name }}</h4>

                        </div>
                @endforeach
            </div>

        </div>
        {{ $group_forms->appends(["group_name" => $keywords->group_name, "status" => $keywords->status])->links() }}
    </div>

@endsection

@section('script')
    <script type="text/javascript" src="/js/search.js"></script>
@endsection
