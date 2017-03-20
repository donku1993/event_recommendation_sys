@extends('layouts.app')

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-2">
                <div class="row">
                    <div class="col-md-3 event-preview">
                        <img src="/img/{{ $event->previewImage }}" alt="" style="width: 210px; height: 210px">
                    </div>
                    <div class="col-md-9 event-information">
                        <div class="eventIdrow">
                            <span class="eventId">#{{ $event->id }}</span>
                            <span><i class="glyphicon glyphicon-fire"></i>活動人氣: </span>
                            <span><i class="glyphicon glyphicon-heart"></i>組織評價: </span>
                        </div>

                        <h1>{{ $event->title }}</h1>
                        <br>
                        <table style="width: inherit">
                            <tbody>
                            <tr>
                                <td><i class="glyphicon glyphicon-calendar"></i>活動開始: {{ $event->startDate }} {{ $event->startTime }}</td>
                                <td><i class="glyphicon glyphicon-calendar"></i>活動結束: {{ $event->endDate }} {{ $event->endTime }} </td>
                            </tr>
                            <tr>
                                <td><i class="glyphicon glyphicon-time"></i>活動時數: {{ $event->getHours() }} hrs</td>
                                <td><i class="glyphicon glyphicon-exclamation-sign"></i>報名截止: {{ $event->signUpEndDate }} {{ $event->signUpEndTime }}</td>
                            </tr>
                            <tr>
                                <td><i class="glyphicon glyphicon-user"></i>可報名人數: {{ $event->numberOfPeople }} 人</td>
                                <td><i class="glyphicon glyphicon-ok"></i>已報名人數: </td>
                            </tr>
                            </tbody>
                        </table>

                        {{--Group can edit, wait for middleware--}}
                        <button type="button" class="btn btn-primary"><a href="/event/{{ $event->id }}/edit">修改</a></button>

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
            </div>
        </div>
    </div>

@endsection

@section('script')




@endsection







