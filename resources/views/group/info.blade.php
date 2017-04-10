@extends('layouts.app')

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2" style="margin-bottom: 20px">
                    <div class="col-md-4 group-preview">
                        <img src="{{ $group->iconPath }}" alt="" style="width: 210px; height: 210px">
                    </div>
                    <div class="col-md-8 group-information">
                        <div class="group-id-row">
                            <span class="groupId">#{{ $group->id }}</span>
                            <span><i class="glyphicon glyphicon-fire"></i>組織人氣: </span>
                            <span><i class="glyphicon glyphicon-heart"></i>組織評價: </span>
                        </div>

                        <h1 class="info-title">{{ $group->name }}</h1>
                        <div class="group-info-row" >
                            <span><i class="glyphicon glyphicon-phone-alt"></i>聯絡電話: {{ $group->phone }}</span><br/>
                            <span><i class="glyphicon glyphicon-envelope"></i>電子信箱: {{ $group->email }}</span><br/>
                            <span><i class="glyphicon glyphicon-map-marker"></i>聯絡地址: {{ $group->address }}</span><br/>

                        </div>
                        <br>



                        {{--Group can edit, wait for middleware--}}
                        @if( $status_array['is_group_manager'] )
                            <a class="btn btn-primary" href="/group/{{ $group->id }}/edit">修改</a>
                        @endif

                    </div>
            </div>

            <div class="group-information-content" >
                        <div class="col-md-8 col-md-offset-2">
                            <div class="panel panel-warning">
                                <div class="panel panel-heading"><i class="glyphicon glyphicon-info-sign"></i>組織簡介</div>
                                <div class="panel panel-body">
                                    {!! $group->introduction !!}
                                </div>
                            </div>
                        </div>
            </div>

            <div class="relate-event" >
                <div class="col-md-8 col-md-offset-2" >
                    <div class="panel panel-primary">
                        <div class="panel panel-heading"><i class="glyphicon glyphicon-star-empty"></i>組織的活動</div>
                        <div class="panel panel-body">

                                @foreach($group->events as $event)
                                        <div class="group-event-item col-md-12">
                                            <div class="col-md-5">
                                                <a href="/event/{{ $event->id }}">
                                                    <img src="{{ $event->iconPath }}" alt="" style="width: 210px; height: 210px">
                                                </a>
                                            </div>

                                            <div class="col-md-7">
                                                <h4 class="lf_title_h4"><i class="glyphicon glyphicon-hand-right" aria-hidden="true"></i> {{ $event->title }}</h4>
                                                <table>
                                                    <tbody>
                                                    <tr>
                                                        <td>
                                                            <i class="glyphicon glyphicon-calendar"></i>{{ $event->startDate }} 到 {{ $event->endDate }}
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <i class="glyphicon glyphicon-time">{{ $event->hours }}</i>hrs
                                                            <i class="glyphicon glyphicon-map-marker"></i>{{  $constant_array['location']['value'][$event->location] }}
                                                            <i class="glyphicon glyphicon-user" aria-hidden="true"></i> 需求{{ $event->numberOfPeople }}人
                                                            <i class="glyphicon glyphicon-tags">{{ $constant_array['event_type']['value'][$event->type] }}</i>
                                                        </td>
                                                    </tr>
                                                    </tbody>
                                                </table>

                                                <label for="content">活動內容:</label><br>
                                                <div class="group-info-event">
                                                    {{ $event->content }}
                                                </div>

                                            </div>

                                        </div>

                            @endforeach
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </div>


@endsection

@section('script')

<script>
    $(function(){
        var len = 250;
        $(".group-info-event").each(function(i){
            if($(this).text().length>len){
                var text=$(this).text().substring(0,len-1)+"...";
                $(this).text(text);
            }
        });
    });
</script>


@endsection







