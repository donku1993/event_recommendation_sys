@extends('layouts.app')

@section('content')

    <div class="container">
        <div class="row profile">
            @include('user.info-left')

            <div class="col-md-9">
                <div class="profile-content">
                    <form id="user-info-form" class="form-horizontal" role="form" >
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label for="name" class="col-md-4 control-label">姓名:</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control" name="name" value="{{ $data['user']->name }}" required autofocus>

                                @if ($errors->has('name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email" class="col-md-4 control-label">E-Mail:</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control" name="email" value="{{ $data['user']->email }}" required>

                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label for="password" class="col-md-4 control-label">密碼:</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control"  name="password" value="{{ $data['user']->password }}" required>

                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="password-confirm" class="col-md-4 control-label">確認密碼:</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control"  name="password_confirmation" required>
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('phone') ? ' has-error' : '' }}">
                            <label for="phone" class="col-md-4 control-label">電話:</label>

                            <div class="col-md-6">
                                <input id="phone" type="text" class="form-control" name="phone" value="{{ $data['user']->phone }}" required>

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
                                <label class="radio-inline">
                                    <input type="radio" id="gender-m" name="gender" value="0" checked> 男
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" id="gender-f" name="gender" value="1"> 女
                                </label>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="career" class="col-md-4 control-label">職業</label>

                            <div class="col-md-6">
                                <select class="form-control" name="career" form="user_register_form" required>
                                    @foreach ($career_array['value'] as $key => $value)
                                        @if( $data['user']->career ==  $key )
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
                                @foreach($available_time_array['value'] as $key => $value)
                                    <label class="checkbox">
                                        @if( $data['user']->available_time[$key] )
                                            <input type="checkbox" checked name="{{ $available_time_array['prefix'] }}_{{ $key }}" > {{ $value }}
                                        @else
                                            <input type="checkbox" name="{{ $available_time_array['prefix'] }}_{{ $key }}" > {{ $value }}
                                        @endif
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="available-area" class="col-md-4 control-label">經常活動的地區:</label>

                            <div class="col-md-6">
                                @foreach($location_array['value'] as $key => $value)
                                    <label class="checkbox">
                                        @if( $data['user']->location[$key] )
                                            <input type="checkbox" checked name="{{ $location_array['prefix'] }}_{{ $key }}" > {{ $value }}
                                        @else
                                            <input type="checkbox" name="{{ $location_array['prefix'] }}_{{ $key }}" > {{ $value }}
                                        @endif
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="allow-email" class="col-md-4 control-label">通過E-mail發送活動邀請:</label>
                            @if( $data['user']->allow_email )
                                <div class="col-md-6">
                                    <label class="radio-inline">
                                        <input type="radio" name="allow_email" checked> 是
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" name="allow_email" > 否
                                    </label>
                                </div>
                            @else
                                <div class="col-md-6">
                                    <label class="radio-inline">
                                        <input type="radio" name="allow_email" > 是
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" name="allow_email" checked> 否
                                    </label>
                                </div>
                            @endif
                        </div>


                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button class="user-info-edit btn btn-primary">
                                    修改
                                </button>
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
        var user_info = $('.user-info');

        user_info.click(function () {
            $('.profile-usermenu li').removeClass('active');
            user_info.parent().addClass('active');
        });
    </script>


@endsection