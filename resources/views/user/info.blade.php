@extends('layouts.app')

@section('content')

    <div class="container">
        <div class="row profile">
        @include('user.info-left')





        </div>
    </div>

@endsection

@section('script')
{{--    <script type="text/javascript">
        var user_info = $('.user-info');

        user_info.click(function () {
            $('.profile-usermenu li').removeClass('active');
            user_info.parent().addClass('active');
        });
    </script>--}}


@endsection