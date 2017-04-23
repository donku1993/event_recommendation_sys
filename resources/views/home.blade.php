@extends('layouts.app')

@section('content')
    @include('layouts.search-template')
    <div class="container">
    <div class="row">

        <div class="col-md-18">
            <div class="form-group">
                <div class="row">
                    <div class="panel-body">
                        <div class="col-md-12 ">

                            <div class="col-md-12">
                                <hr style="width: 100%; color: black; height: 1px; background-color:gray;" >
                            </div>

                            <a data-remote="true" href="javascript:void(0)" id="latest">
                                <h4 style=""><i class="glyphicon glyphicon-star-empty"></i> 最新活動</h4>
                            </a>
                            <hr>
                            @foreach($newest_events as $newest_event)
                                <div class="event-item col-md-6">
                                    <div class="col-md-5">
                                        <a href="/event/{{ $newest_event->id }}">
                                            <img src="{{ $newest_event->iconPath }}" alt="" style="width: 210px; height: 210px">
                                        </a>
                                    </div>

                                    <div class="col-md-5">
                                        <h4 class="lf_title_h4"><i class="glyphicon glyphicon-hand-right" aria-hidden="true"></i> {{ $newest_event->title }}</h4>

                                        <table>
                                            <tbody>
                                            <tr>
                                                <td><i class="glyphicon glyphicon-calendar">{{ $newest_event->startDate->toDateString() }}</i></td>
                                                <td><i class="glyphicon glyphicon-time">{{ $newest_event->hours }}</i>
                                                    hrs
                                                </td>
                                            </tr>

                                            <tr>
                                                <td><i class="glyphicon glyphicon-map-marker"></i>{{ $constant_array['location']['value'][$newest_event->location] }}</td>

                                                <td><i class="glyphicon glyphicon-user" aria-hidden="true"></i> 需求{{ $newest_event->numberOfPeople }}人</td>
                                            </tr>

                                            <tr>
                                                <td><i class="glyphicon glyphicon-tags"></i>{{ $constant_array['event_type']['value'][$newest_event->type] }}</td>
                                            </tr>

                                            </tbody>
                                        </table>
                                        <button type="button" class="btn btn-success btn-lg btn-block"><a href="/event/{{ $newest_event->id }}" style="color: #f5f8fa"><i class="fa fa-pencil-square" aria-hidden="true"></i>查看詳情</a></button>
                                    </div>


                                </div>
                            @endforeach


                            <div class="col-md-12">
                                <hr style="width: 100%; color: black; height: 1px; background-color:gray;" >
                            </div>

                            <a data-remote="true" href="javascript:void(0)" id="most_popular">
                                <h4 style=""><i class="glyphicon glyphicon-fire"></i> 最受歡迎活動</h4>
                            </a>
                            <hr>
                            @foreach($most_popular_events as $most_popular_event)
                                <div class="event-item col-md-6">
                                    <div class="col-md-5">
                                        <a href="/event/{{ $most_popular_event->id }}">
                                            <img src="{{ $most_popular_event->iconPath }}" alt="" style="width: 210px; height: 210px">
                                        </a>
                                    </div>

                                    <div class="col-md-5">
                                        <h4 class="lf_title_h4"><i class="glyphicon glyphicon-hand-right" aria-hidden="true"></i> {{ $most_popular_event->title }}</h4>

                                        <table>
                                            <tbody>
                                            <tr>
                                                <td><i class="glyphicon glyphicon-calendar">{{ $most_popular_event->startDate->toDateString() }}</i></td>
                                                <td><i class="glyphicon glyphicon-time">{{ $most_popular_event->hours }}</i>
                                                    hrs
                                                </td>
                                            </tr>

                                            <tr>
                                                <td><i class="glyphicon glyphicon-map-marker"></i>{{ $constant_array['location']['value'][$most_popular_event->location] }}</td>

                                                <td><i class="glyphicon glyphicon-user" aria-hidden="true"></i> 需求{{ $most_popular_event->numberOfPeople }}人</td>
                                            </tr>

                                            <tr>
                                                <td><i class="glyphicon glyphicon-tags"></i>{{ $constant_array['event_type']['value'][$most_popular_event->type] }}</td>
                                            </tr>

                                            </tbody>
                                        </table>
                                        <button type="button" class="btn btn-success btn-lg btn-block"><a href="/event/{{ $most_popular_event->id }}" style="color: #f5f8fa"><i class="fa fa-pencil-square" aria-hidden="true"></i>查看詳情</a></button>
                                    </div>


                                </div>
                            @endforeach

                            <div class="col-md-12">
                                <hr style="width: 100%; color: black; height: 1px; background-color:gray;" >
                            </div>

                            <a data-remote="true" href="javascript:void(0)" id="recommend">
                                <h4 style=""><i class="glyphicon glyphicon-fire"></i> 推薦活動</h4>
                            </a>
                            <hr>
                            @foreach($recommend_events as $recommend_event)
                                <div class="event-item col-md-6">
                                    <div class="col-md-5">
                                        <a href="/event/{{ $recommend_event->id }}">
                                            <img src="{{ $recommend_event->iconPath }}" alt="" style="width: 210px; height: 210px">
                                        </a>
                                    </div>

                                    <div class="col-md-5">
                                        <h4 class="lf_title_h4"><i class="glyphicon glyphicon-hand-right" aria-hidden="true"></i> {{ $recommend_event->title }}</h4>

                                        <table>
                                            <tbody>
                                            <tr>
                                                <td><i class="glyphicon glyphicon-calendar">{{ $recommend_event->startDate->toDateString() }}</i></td>
                                                <td><i class="glyphicon glyphicon-time">{{ $recommend_event->hours }}</i>
                                                    hrs
                                                </td>
                                            </tr>

                                            <tr>
                                                <td><i class="glyphicon glyphicon-map-marker"></i>{{ $constant_array['location']['value'][$recommend_event->location] }}</td>

                                                <td><i class="glyphicon glyphicon-user" aria-hidden="true"></i> 需求{{ $recommend_event->numberOfPeople }}人</td>
                                            </tr>

                                            <tr>
                                                <td><i class="glyphicon glyphicon-tags"></i>{{ $constant_array['event_type']['value'][$recommend_event->type] }}</td>
                                            </tr>

                                            </tbody>
                                        </table>
                                        <button type="button" class="btn btn-success btn-lg btn-block"><a href="/event/{{ $recommend_event->id }}" style="color: #f5f8fa"><i class="fa fa-pencil-square" aria-hidden="true"></i>查看詳情</a></button>
                                    </div>


                                </div>
                            @endforeach
                        </div>

                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

@endsection

@section('script')
    <script type="text/javascript" src="/js/search.js"></script>
@endsection

@section('footer')
    @include('layouts.footer')
@endsection
