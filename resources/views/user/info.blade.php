@extends('layouts.app')

@section('content')

    <div class="container">
        <div class="row profile">
            @include('user.info-left')

            <div class="col-md-9">

                <div class="profile-content">
                    <form id="user-info-form" class="form-horizontal" role="form" action="{{ route('user.update', $user->id) }}" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="_method" value="PUT">
                        {{ csrf_field() }}

                        <div class="form-group">
                            <label for="name" class="col-md-4 control-label">姓名:</label>

                            <div class="col-md-6">
                                <p class="form-control">{{ $user->name }}</p>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="career" class="col-md-4 control-label">職業:</label>
                            <div class="col-md-6">
                                <p class="form-control">{{ $constant_array['career']['value'][$user->career] }}</p>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="year_of_volunteer" class="col-md-4 control-label">志願者年資:</label>
                            <div class="col-md-6">
                                <p class="form-control">{{ $constant_array['year_of_volunteer']['value'][$user->year_of_volunteer] }}</p>
                            </div>
                        </div>



                        <div class="form-group">
                            <label for="gender" class="col-md-4 control-label">性別:</label>

                            <div class="col-md-6">
                                @if( $user->gender == 0 )
                                    <label class="control-label">男</label>
                                @else
                                    <label class="control-label">女</label>
                                @endif

                            </div>
                        </div>


                        <div class="form-group">
                            <label for="available-time" class="col-md-4 control-label">比較有空的時間:</label>

                            <div class="col-md-6">
                                @foreach($constant_array['available_time']['value'] as $key => $value)
                                        @if( $user->available_time[$key] )
                                            <p class="form-control">{{ $value }}</p>
                                        @endif
                                @endforeach
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="available-area" class="col-md-4 control-label">經常活動的地區:</label>

                            <div class="col-md-6">
                                @foreach($constant_array['location']['value'] as $key => $value)
                                        @if( $user->available_area[$key] )
                                            <label class="control-label">{{ $value }}</label><br>
                                        @endif
                                @endforeach

                            </div>
                        </div>


                        <div class="form-group">
                            <label for="self_introduction" class="col-md-4 control-label">自我介紹:</label>
                            <div class="col-md-6">
                                <textarea rows="4" cols="50" disabled="">{{ $user->self_introduction }}</textarea>
                        </div>


                    </form>
                </div>
            </div>


        </div>
    </div>

@endsection

@section('script')
    <script type="text/javascript">
        activeClass();

    </script>


@endsection