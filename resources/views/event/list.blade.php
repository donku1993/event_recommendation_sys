@extends('layouts.app')

@section('content')
    @include('layouts.search-template')

    <div class="container">
        <div class="row">

        @foreach($events as $event)
            <div class="event-item col-md-5">
                <div class="col-md-6">
                    <a href="/event/{{ $event->id }}">
                        <img src="/img/{{ $event->previewImage }}" alt="" style="width: 210px; height: 210px">
                    </a>
                </div>

                <div class="col-md-5">
                    <h4 class="lf_title_h4"><i class="glyphicon glyphicon-hand-right" aria-hidden="true"></i> {{ $event->title }}</h4>

                    <table>
                        <tbody>
                        <tr>
                            <td><i class="glyphicon glyphicon-calendar">{{ $event->startDate }}</i></td>
                            <td><i class="glyphicon glyphicon-time"></i>
                                hrs
                            </td>
                        </tr>

                        <tr>
                            <td><i class="glyphicon glyphicon-map-marker"></i>{{ $constant_array['location']['value'][$event->location] }}</td>

                            <td><i class="glyphicon glyphicon-user" aria-hidden="true"></i> 需求{{ $event->numberOfPeople }}人</td>
                        </tr>

                        <tr>
                            <td><i class="glyphicon glyphicon-tags"></i>{{ $constant_array['event_type']['value'][$event->type] }}</td>
                        </tr>

                        </tbody>
                    </table>
                    <button type="button" class="btn btn-success btn-lg btn-block"><a href="" style="color: #f5f8fa"><i class="fa fa-pencil-square" aria-hidden="true"></i>我要報名</a></button>
                </div>


            </div>
            <div class="col-md-1"></div>
        @endforeach
        </div>


    </div>

@endsection

@section('script')
    <script type="text/javascript" src="/js/search.js"></script>
@endsection
