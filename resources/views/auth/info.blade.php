@extends('layouts.app')

@section('content')

    <div class="container">
        <div class="row profile">
            <div class="col-md-3">
                <div class="profile-sidebar">
                    <!-- SIDEBAR USERPIC -->
                    <div class="profile-userpic">
                        <img src="http://kathirose.com/wp-content/uploads/2016/05/Hair-Icon-180x180.jpg" class="img-responsive" alt="">
                    </div>
                    <div class="profile-userpic-edit profile-userbuttons">
                        <button class="btn btn-warning btn-sm">更換圖片</button>
                    </div>
                    <!-- END SIDEBAR USERPIC -->
                    <!-- SIDEBAR USER TITLE -->
                    <div class="profile-usertitle">
                        <div class="profile-usertitle-name">
                            Marcus Doe
                        </div>
                        <div class="profile-usertitle-job">
                            Developer
                        </div>
                    </div>
                    <!-- END SIDEBAR USER TITLE -->
                    <!-- SIDEBAR BUTTONS -->
                    <div class="profile-userbuttons">
                        <button type="button" class="btn btn-success btn-sm">Follow</button>
                        <button type="button" class="btn btn-danger btn-sm">Message</button>
                    </div>
                    <!-- END SIDEBAR BUTTONS -->
                    <!-- SIDEBAR MENU -->
                    <div class="profile-usermenu">
                        <ul class="nav">
                            <li class="active">
                                <a href="#" class="overview">
                                    <i class="glyphicon glyphicon-home"></i>
                                    總覽 </a>
                            </li>
                            <li>
                                <a href="#" class="user-info">
                                    <i class="glyphicon glyphicon-user"></i>
                                    個人資料與設定 </a>
                            </li>
                            <li>
                                <a href="#" class="user-events">
                                    <i class="glyphicon glyphicon-flag"></i>
                                    活動 </a>
                            </li>
                            <li>
                                <a href="#">
                                    <i class="user-groups glyphicon glyphicon-tower"></i>
                                     團體</a>
                            </li>
                            <li>
                                <a href="#">
                                    <i class="user-timetable glyphicon glyphicon-time"></i>
                                    時間表</a>
                            </li>
                        </ul>
                    </div>
                    <!-- END MENU -->
                </div>


            </div>

            <div class="col-md-9">
                <div class="profile-content">
                    Some user related content goes here...
                </div>
            </div>


            <div class="user-info-content" style="display: none">
                <form class="form-horizontal" role="form" method="PUT" ">
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
                                <input type="radio" id="gender-m" name="gender" value="1" checked> 男
                            </label>
                            <label class="radio-inline">
                                <input type="radio" id="gender-f" name="gender" value="0"> 女
                            </label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="career" class="col-md-4 control-label">職業</label>

                        <div class="col-md-6">
                            <select class="form-control" name="career" form="user_register_form" required>
                                @foreach ($career as $key => $value)
                                    <option value="{{ $key }}">{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="available-time" class="col-md-4 control-label">比較有空的時間:</label>

                        <div class="col-md-6">
                            @foreach($available_time_array as $key => $value)
                                <label class="checkbox-inline">
                                    <input type="checkbox" name="$available_time_{{ $key }}" > {{ $value }}
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="allow-email" class="col-md-4 control-label">經常活動地區:</label>

                        <div class="col-md-6">
                            @foreach($available_area_array as $key => $value)
                                <label class="checkbox-inline">
                                    <input type="checkbox" name="$available_area_{{ $key }}" > {{ $value }}
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="available-area" class="col-md-4 control-label">通過E-mail發送活動邀請:</label>

                        <div class="col-md-6">
                            <label class="radio-inline">
                                <input type="radio" name="allow_email" > 是
                            </label>
                            <label class="radio-inline">
                                <input type="radio" name="allow_email" > 否
                            </label>
                        </div>
                    </div>


                    <div class="form-group">
                        <div class="col-md-6 col-md-offset-4">
                            <button type="submit" class="btn btn-primary">
                                修改
                            </button>
                        </div>
                    </div>
                </form>
            </div>



    </div>
    </div>

@endsection

@section('script')
    <script type="text/javascript">
        var user_info = $('.user-info');
        var pro_content = $('.profile-content');

        user_info.click(function () {
            $('.profile-usermenu li').removeClass('active');
            user_info.parent().addClass('active');
            pro_content.html($('.user-info-content').html() );

        });

    </script>


@endsection