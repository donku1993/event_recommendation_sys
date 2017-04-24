@extends('layouts.app')

@section('content')
    @include('layouts.search-template')

    <div class="container">
        <div class="row">

        @foreach($events as $event)
            <div class="event-item col-md-5">
                <div class="col-md-6">
                    <a href="/event/{{ $event->id }}">
                        <img src="{{ $event->iconPath }}" alt="" style="width: 210px; height: 210px">
                    </a>
                </div>

                <div class="col-md-5">
                    <h4 class="lf_title_h4" style="text-align: center;width: 210px"><i class="glyphicon glyphicon-hand-right" aria-hidden="true"></i> {{ $event->title }}</h4>

                    <table>
                        <tbody>
                        <tr>
                            <td><i class="glyphicon glyphicon-calendar">{{ $event->startDate->toDateString() }}</i></td>
                            <td><i class="glyphicon glyphicon-time">{{ $event->hours }}</i>
                                hrs
                            </td>
                        </tr>

                        <tr>
                            <td><i class="glyphicon glyphicon-map-marker"></i>{{ $constant_array['location']['value'][$event->location] }}</td>

                            <td><i class="glyphicon glyphicon-user" aria-hidden="true"></i> 需求{{ $event->numberOfPeople }}人</td>
                        </tr>

                        <tr>
                            <td><i class="glyphicon glyphicon-tags"></i>{{ $constant_array['event_type']['value'][$event->type] }}</td>
                            <td><i class="glyphicon glyphicon-pencil"></i>報名{{ $event->numberOfJoin }} 人</td>
                        </tr>

                        </tbody>
                    </table>
                    <a href="/event/{{ $event->id }}" style="color: #f5f8fa"><button type="button" class="btn btn-success btn-lg btn-block"><i class="fa fa-pencil-square" aria-hidden="true"></i>查看詳情</button></a>
                </div>


            </div>
            <div class="col-md-1"></div>
        @endforeach
        </div>

        {{ $events->appends(["location" => $keywords->location, "type" => $keywords->type, "time_from" => $keywords->time_from, "time_to" => $keywords->time_to, "event_name" => $keywords->event_name])->links() }}

    </div>

@endsection

@section('script')
    <script type="text/javascript" src="/js/search.js"></script>
@endsection

@section('footer')
    @include('layouts.footer')
@endsection
