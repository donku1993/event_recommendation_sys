@extends('layouts.app')

@section('content')

        @include('layouts.search-template')
        @if( count($events) === 0 )
            <strong>沒有找到相應活動!</strong>
        @else
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        @foreach($events as $event)
                        <div class="search-event-item col-md-4" style="margin-bottom: 42px">
                            <div class="col-md-6">
                                <a href="/event/{{ $event->id }}">
                                    <img src="/img/{{ $event->previewImage }}" alt="" style="width: 160px; height: 160px">
                                </a>
                            </div>


                            <div class="col-md-6">
                                <h4 class="search-lf_title_h4" style="color:#FF6F6F ;"><i class="glyphicon glyphicon-hand-right" aria-hidden="true"></i> {{ $event->title }}</h4>
                                <table style="color: #7188c7;">
                                    <tbody>
                                    <tr>
                                        <td><i class="glyphicon glyphicon-calendar"></i>{{ $event->getStartDate() }}</td>
                                    </tr>
                                    <tr>
                                        <td><i class="glyphicon glyphicon-time"></i>
                                            {{ $event->getHours() }}hrs
                                        </td>
                                    </tr>

                                    <tr>
                                        <td><i class="glyphicon glyphicon-map-marker"></i>{{ $location_array[$event->location] }}</td>
                                    </tr>
                                    <tr>
                                        <td><i class="glyphicon glyphicon-user" aria-hidden="true"></i> 需求{{ $event->numberOfPeople }}人</td>
                                    </tr>

                                    <tr>
                                        <td><i class="glyphicon glyphicon-tags"></i>{{ $type_array[$event->type] }}</td>
                                    </tr>

                                    </tbody>
                                </table>

                            </div>

                            <button type="button" class="btn btn-success btn-lg btn-block"><a href="" style="color: #f5f8fa"><i class="fa fa-pencil-square" aria-hidden="true"></i>我要報名</a></button>

                        </div>
                        @endforeach
                </div>
            </div>

                {{ $events->links() }}
        @endif
            </div>
@endsection

@section('script')
    <script type="text/javascript" src="/js/search.js"></script>
@endsection

