<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link rel="stylesheet" href="/css/app.css">
    <link rel="stylesheet" href="/css/home.css">
    <link rel="stylesheet" href="/css/summernote.css">
    <link rel="stylesheet" href="/css/jquery.datetimepicker.css">
    <link href="css/bootstrap.icon-large.min.css" rel="stylesheet">

    <!-- Scripts -->
    <script>
        window.Laravel = {!! json_encode([
            'csrfToken' => csrf_token(),
        ]) !!};
    </script>

    <script type="text/javascript" src="/js/myjs.js"></script>
    <script type="text/javascript" src="/js/app.js"></script>

</head>

<body>
<div id="app">
    <nav class="navbar navbar-default navbar-static-top">
        <div class="container">
            <div class="navbar-header">

                <!-- Collapsed Hamburger -->
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                    <span class="sr-only">Toggle Navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>

                <!-- Branding Image -->
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
            </div>

            <div class="collapse navbar-collapse" id="app-navbar-collapse">
                <!-- Left Side Of Navbar -->
                <ul class="nav navbar-nav">
                    &nbsp;
                </ul>

                <!-- Right Side Of Navbar -->
                <ul class="nav navbar-nav navbar-right">

                    @if (Auth::guest())
                        <li><a href="{{ route('login') }}">Login</a></li>
                        <li><a href="{{ route('register') }}">Register</a></li>
                    @else
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                <img class="img-circle" src="{{ Auth::user()->iconPath }}" style="width:20px; height:20px;">
                                {{ Auth::user()->name }} <span class="caret"></span>
                            </a>

                            <ul class="dropdown-menu" role="menu">
                                <li>
                                    <a href="{{ route('user.info', Auth::user()->id) }}">
                                        Profile
                                    </a>
                                    <a href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        Logout
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        {{ csrf_field() }}
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>


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


</div>


