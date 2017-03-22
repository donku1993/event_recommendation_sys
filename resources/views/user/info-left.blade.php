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
                Daniel Lo
            </div>
            <div class="profile-usertitle-job">
                Developer
            </div>
        </div>
        <!-- END SIDEBAR USER TITLE -->
        <!-- SIDEBAR BUTTONS -->
        <div class="profile-userbuttons">
            <button type="button" class="btn btn-success btn-sm">追隨</button>
            <button type="button" class="btn btn-danger btn-sm">訊息</button>
        </div>
        <!-- END SIDEBAR BUTTONS -->
        <!-- SIDEBAR MENU -->
        <div class="profile-usermenu">
            <ul class="nav">
                <li class="active">
                    <a href="/user/{{ $user->id }}" class="overview">
                        <i class="glyphicon glyphicon-home"></i>
                        總覽 </a>
                </li>
                <li>
                    <a href="/user/{{ $user->id }}/edit" class="user-info active">
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
                        組織</a>
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