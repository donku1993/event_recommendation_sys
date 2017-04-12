@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">註冊帳號</div>
                <div class="panel-body">
                    <form class="form-horizontal" role="form" method="POST" id="user_register_form" action="{{ route('register') }}">
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label for="name" class="col-md-4 control-label">姓名:</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" required autofocus>

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
                                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required>

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
                                <input id="password" type="password" class="form-control" name="password" required>

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
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('phone') ? ' has-error' : '' }}">
                            <label for="phone" class="col-md-4 control-label">電話:</label>

                            <div class="col-md-6">

                                <input id="phone" type="text" class="form-control" name="phone" required>


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
                                    @foreach ($constant_array['career']['value'] as $key => $value)
                                        <option value="{{ $key }}">{{ $value }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="career" class="col-md-4 control-label">志願者年資</label>

                            <div class="col-md-6">
                                <input type="number" min="0" name="year_of_volunteer" class="check-value form-control" placeholder="請輸入年資" >
                            </div>
                        </div>



                        <div class="form-group">
                            <label for="available-time" class="col-md-4 control-label">比較有空的時間:</label>

                            <div class="col-md-6">
                                @foreach($constant_array['available_time']['value'] as $key => $value)
                                    <label class="checkbox-inline">
                                        <input type="checkbox" name="available_time_{{ $key }}" value="true"> {{ $value }}
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="available-area" class="col-md-4 control-label">經常活動地區:</label>

                            <div class="col-md-6">
                                @foreach($constant_array['location']['value'] as $key => $value)
                                    <label class="checkbox-inline">
                                        <input type="checkbox" name="available_area_{{ $key }}" value="true"> {{ $value }}
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="interest_skills" class="col-md-4 control-label">個人技能和興趣:</label>

                            <div class="col-md-6">

                                @foreach($constant_array['interest_skills']['value'] as $key => $value)
                                    <label class="checkbox-inline">
                                        <input type="checkbox" name="interest_skills_{{ $key }}" > {{ $value }}
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="allow-email" class="col-md-4 control-label">接收系統發送的活動邀請E-mail:</label>

                            <div class="col-md-6">
                                <label class="radio-inline">
                                    <input type="radio" name="allow_email" value="true" checked> 是
                                </label>
                                 <label class="radio-inline">
                                    <input type="radio" name="allow_email" value="false"> 否
                                </label>
                            </div>
                        </div>


                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    註冊
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
