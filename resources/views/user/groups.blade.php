@extends('layouts.app')

@section('content')

    <div class="container">
        <div class="row profile">
            @include('user.info-left')

            <div class="col-md-9">

                <div class="profile-content">

                    <div class="col-md-12 panel panel-danger">
                            <h4 style=""><i class="glyphicon glyphicon-tower"></i> 已加入的組織</h4>
                        <hr>

                        @foreach ($user->groups as $group)
                            <div class="col-md-3 recommend-events-holder">
                                <a href="javascript:viod(0)" id="check-more" class="btn btn-block btn-warning" style="opacity: 0;width: 120px;margin-left: 15px;margin-right: 15px;position: absolute; "><i class="glyphicon glyphicon-exclamation-sign"></i>查看詳情</a>

                                <a class="img-holder" href="/group/{{ $group->id }}">
                                    <img src="{{ $group->iconPath }}" alt="" style="width: 150px; height: 150px;">
                                </a>
                                <div class="col-md-12" style="padding-left: 0px;">
                                    <h4 class="lf_title_h4">{{ $group->name }}</h4>
                                </div>

                            </div>
                        @endforeach
                    </div>

                    <div class="col-md-12 panel panel-success">
                            <h4 style=""><i class="glyphicon glyphicon-bookmark"></i> 已標記的組織</h4>
                        <hr>
                        @foreach ($user->markedGroup as $markedGroup)
                            <div class="col-md-3 recommend-events-holder">
                                <a href="javascript:viod(0)" id="check-more" class="btn btn-block btn-warning" style="opacity: 0;width: 120px;margin-left: 15px;margin-right: 15px;position: absolute; "><i class="glyphicon glyphicon-exclamation-sign"></i>查看詳情</a>

                                <a class="img-holder" href="/group/{{ $markedGroup->id }}">
                                    <img src="{{ $markedGroup->iconPath }}" alt="" style="width: 150px; height: 150px;">
                                </a>

                                <div class="col-md-12" style="padding-left: 0px;">
                                    <h4 class="lf_title_h4">{{ $markedGroup->name }}</h4>
                                </div>

                            </div>
                        @endforeach
                    </div>

                </div>
            </div>


        </div>
    </div>

@endsection

@section('script')
    <script type="text/javascript">
        activeClass();

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