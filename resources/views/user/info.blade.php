@extends('layouts.app')

@section('content')

    <div class="container">
        <div class="row profile">
        @include('user.info-left')

            <div class="col-md-9">
                <div class="panel panel-default">
                    <div class="panel-body">






                    </div>
                </div>
            </div>

            @foreach($user->groups as $group)

            @endforeach





        </div>
    </div>

@endsection

@section('script')
    <script type="text/javascript">

    </script>




@endsection