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

                            <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                                <label for="name" class="col-md-4 control-label">姓名:</label>

                                <div class="col-md-6">
                                    <input id="name" type="text" class="form-control" name="name" value="{{ $user->name }}" required autofocus>

                                    @if ($errors->has('name'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="email" class="col-md-4 control-label">E-Mail:</label>

                                <div class="col-md-6">
                                    <input id="email" type="email" class="form-control" name="email" value="{{ $user->email }}" required>

{{--                                    @if ($errors->has('email'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                    @endif--}}
                                </div>
                            </div>


                            <div class="form-group{{ $errors->has('phone') ? ' has-error' : '' }}">
                                <label for="phone" class="col-md-4 control-label">電話:</label>

                                <div class="col-md-6">
                                    <input id="phone" type="text" class="form-control" name="phone" value="{{ $user->phone }}" required>

                                    @if ($errors->has('phone'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('phone') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>



                            <div class="form-group">
                                <label for="gender" class="col-md-4 control-label">性別</label>

                                <div class="col-md-6">
                                        @if( $user->gender == 0 )
                                            <label class="radio-inline">
                                                <input type="radio" id="gender-m" name="gender" value="0" checked> 男
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" id="gender-f" name="gender" value="1"> 女
                                            </label>
                                        @else
                                            <label class="radio-inline">
                                                <input type="radio" id="gender-m" name="gender" value="0" > 男
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" id="gender-f" name="gender" value="1" checked> 女
                                            </label>
                                        @endif


                                </div>
                            </div>

                            <div class="form-group">
                                <label for="career" class="col-md-4 control-label">職業</label>

                                <div class="col-md-6">
                                    <select class="form-control" name="career" required>
                                        @foreach ($constant_array['career']['value'] as $key => $value)
                                            @if( $user->career ==  $key )
                                                <option selected value="{{ $key }}">{{ $value }}</option>
                                            @else
                                                <option value="{{ $key }}">{{ $value }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="year_of_volunteer" class="col-md-4 control-label">志願者年資</label>

                                <div class="col-md-6">
                                    <select class="form-control" name="year_of_volunteer" required>
                                        @foreach ($constant_array['year_of_volunteer']['value'] as $key => $value)
                                            @if( $user->year_of_volunteer ==  $key )
                                                <option selected value="{{ $key }}">{{ $value }}</option>
                                            @else
                                                <option value="{{ $key }}">{{ $value }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="available-time" class="col-md-4 control-label">比較有空的時間:</label>

                                <div class="col-md-6">
                                    @foreach($constant_array['available_time']['value'] as $key => $value)
                                        <label class="checkbox">
                                            @if( $user->available_time[$key] )
                                                <input value="true" type="checkbox" checked name="{{ $constant_array['available_time']['prefix'] }}_{{ $key }}" > {{ $value }}
                                            @else
                                                <input value="true" type="checkbox" name="{{ $constant_array['available_time']['prefix'] }}_{{ $key }}" > {{ $value }}
                                            @endif
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="available-area" class="col-md-4 control-label">經常活動的地區:</label>

                                <div class="col-md-6">
                                    @foreach($constant_array['location']['value'] as $key => $value)
                                        <label class="checkbox">
                                            @if( $user->available_area[$key] )
                                                <input value="true" type="checkbox" checked name="available_area_{{ $key }}" > {{ $value }}
                                            @else
                                                <input value="true" type="checkbox" name="available_area_{{ $key }}" > {{ $value }}
                                            @endif
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="self_introduction" class="col-md-4 control-label">自我介紹:</label>
                                <div class="col-md-6">
                                    <textarea name="self_introduction" rows="4" cols="50">{{ $user->self_introduction }}</textarea>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="allow-email" class="col-md-4 control-label">通過E-mail發送活動邀請:</label>
                                @if( $user->allow_email )
                                    <div class="col-md-6">
                                        <label class="radio-inline">
                                            <input type="radio" name="allow_email" value="true" checked> 是
                                        </label>
                                        <label class="radio-inline">
                                            <input type="radio" name="allow_email" value="false"> 否
                                        </label>
                                    </div>
                                @else
                                    <div class="col-md-6">
                                        <label class="radio-inline">
                                            <input type="radio" name="allow_email" value="true"> 是
                                        </label>
                                        <label class="radio-inline">
                                            <input type="radio" name="allow_email" value="false" checked> 否
                                        </label>
                                    </div>
                                @endif
                            </div>


                            <div class="form-group">
                                <div class="col-md-6 col-md-offset-3">
                                        <input type="submit" class="user-info-edit btn btn-primary btn-block" value="修改">

                                </div>
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