@extends('layouts.app')

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-2">
                <div class="row">
                    <div class="col-md-3 event-preview">
                        <img src="{{ $event->iconPath }}" alt="" style="width: 210px; height: 210px">
                    </div>
                    <div class="col-md-9 event-information">
                        <div class="eventIdrow">
                            <span class="eventId">#{{ $event->id }}</span>
                            <span><i class="glyphicon glyphicon-fire"></i>活動人氣: </span>
                            <span><i class="glyphicon glyphicon-heart"></i>組織評價: </span>
                        </div>

                        <h1 class="info-title">{{ $event->title }}</h1>
                        <br>
                        <table style="width: inherit">
                            <tbody>
                            <tr>
                                <td><i class="glyphicon glyphicon-calendar"></i>活動開始: {{ $event->startDate }} {{ $event->startTime }}</td>
                                <td><i class="glyphicon glyphicon-calendar"></i>活動結束: {{ $event->endDate }} {{ $event->endTime }} </td>
                            </tr>
                            <tr>
                                <td><i class="glyphicon glyphicon-time"></i>活動時數: hrs</td>
                                <td><i class="glyphicon glyphicon-exclamation-sign"></i>報名截止: {{ $event->signUpEndDate }} {{ $event->signUpEndTime }}</td>
                            </tr>
                            <tr>
                                <td><i class="glyphicon glyphicon-user"></i>可報名人數: {{ $event->numberOfPeople }} 人</td>
                                <td><i class="glyphicon glyphicon-ok"></i>已報名人數: </td>
                            </tr>
                            </tbody>
                        </table>

                        {{--Group can edit, wait for middleware--}}
                        @if( $status_array['is_event_manager'] )
                            <a class="btn btn-primary" href="/event/{{ $event->id }}/edit">修改</a>
                        @endif
                        @if( $status_array['is_login'] )
                            <a class="btn btn-success" href="/event/{{ $event->id }}/join">報名</a>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="event-information-content">
        <div class="container">
            <div class="row">
                <div class="col-md-8 col-md-offset-2">
                    <div class="panel panel-success">
                        <div class="panel panel-heading"><i class="glyphicon glyphicon-info-sign"></i>活動內容</div>
                        <div class="panel panel-body">
                            {!! $event->content !!}
                        </div>
                    </div>

                    <div class="panel panel-danger">
                        <div class="panel panel-heading"><i class="glyphicon glyphicon-info-sign"></i>活動流程</div>
                        <div class="panel panel-body">
                            {!! $event->schedule !!}
                        </div>
                    </div>

                    <div class="panel panel-warning">
                        <div class="panel panel-heading"><i class="glyphicon glyphicon-info-sign"></i>活動需求</div>
                        <div class="panel panel-body">
                            {!! $event->requirement !!}
                        </div>
                    </div>

                    <div class="panel panel-info">
                        <div class="panel panel-heading"><i class="glyphicon glyphicon-info-sign"></i>備註事項</div>
                        <div class="panel panel-body">
                            {!! $event->remark !!}
                        </div>
                    </div>
                </div>

                @if( $status_array['is_event_manager'] )
                    <div class="right-nav" >
                        <div class="col-md-2 pull-right">
                            <div class="panel panel-default">
                                <div class="panel panel-heading">
                                    已參加的成員
                                </div>
                                <div class="panel panel-body">
                                    <a class="check-all" href="/event/{{ $event->id }}/member">
                                        <i>查看全部</i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif



            </div>
        </div>
    </div>

@endsection

@section('script')




@endsection







