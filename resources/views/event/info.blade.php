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
                        @if( $status_array['is_event_manager'] || $status_array['is_admin'] )
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

                    @if( $status_array['is_participant_can_evaluate'] )
                        <div class="panel panel-success">
                        <div class="panel-heading">評分</div>
                        <div class="panel-body">
                            <select class="check-value form-control" name="grade">
                                @foreach ($constant_array['event_evaulation']['value'] as $key => $value)
                                    <option value="{{ $key }}">{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    @endif


                </div>

                @if( $status_array['is_event_manager'] )
                    <div class="right-nav" style="text-align: center">
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

                <div class="col-md-12 ">
                    <hr style="width: 100%; color: black; height: 1px; background-color:gray;" >
                    <a data-remote="true" href="javascript:void(0)" id="latest">
                        <h4 style=""><i class="glyphicon glyphicon-fire"></i> 推薦活動</h4>
                    </a>
                    <hr>

                    @foreach ($also_view_events as $also_view_event)
                        <div class="col-md-3 recommend-events-holder">
                            <a href="javascript:viod(0)" id="check-more" class="btn btn-block btn-warning" style="opacity: 0;width: 180px;margin-left: 15px;margin-right: 15px;position: absolute; "><i class="glyphicon glyphicon-exclamation-sign"></i>查看詳情</a>

                            <a class="img-holder" href="/event/{{ $also_view_event->id }}">
                                <img src="{{ $also_view_event->icon_image }}" alt="" style="width: 210px; height: 210px;">
                            </a>


                            <div class="col-md-10" style="padding-left: 0px;">
                                <h4 class="lf_title_h4"><i class="glyphicon glyphicon-hand-right" aria-hidden="true"></i> {{ $also_view_event->title }}</h4>

                                <table class="recommend-events">
                                    <tbody>
                                    <tr>
                                        <td style="padding-right: 40px"><i class="glyphicon glyphicon-calendar">{{ $also_view_event->startDate->toDateString() }}</i></td>
                                        <td><i class="glyphicon glyphicon-time">{{ $also_view_event->hours }}</i>
                                            hrs
                                        </td>
                                    </tr>

                                    <tr>
                                        <td><i class="glyphicon glyphicon-map-marker"></i>{{ $constant_array['location']['value'][$also_view_event->location] }}</td>

                                        <td><i class="glyphicon glyphicon-user" aria-hidden="true"></i> 需求{{ $also_view_event->numberOfPeople }}人</td>
                                    </tr>

                                    <tr>
                                        <td><i class="glyphicon glyphicon-tags"></i>{{ $constant_array['event_type']['value'][$event->type] }}</td>
                                    </tr>

                                    </tbody>
                                </table>
                            </div>

                        </div>
                    @endforeach
                </div>

            </div>
        </div>
    </div>

@endsection

@section('script')

    <script>
        $('.img-holder').hover(function () {
            $(this).css("opacity","0.2");
            $(this).prev('a').css({
                "opacity":"1"
            });
        });
        $('.img-holder').mouseleave(function () {
            $(this).css("opacity","1");
            $(this).prev('a').css({
                "opacity":"0"
            });
        });
    </script>

@endsection

@section('footer')
    @include('layouts.footer')
@endsection







