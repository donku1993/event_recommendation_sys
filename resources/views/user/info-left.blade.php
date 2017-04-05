<div class="col-md-3">
    <div class="profile-sidebar">
        <!-- SIDEBAR USERPIC -->
        <div class="profile-userpic">
            <img src="{{ $user->icon_image }}" style="width: 180px;height: 180px;" class="img-responsive" alt="Icon">
        </div>
        <div class="profile-userpic-edit profile-userbuttons">
            <button onclick="uploadImage();openUploadIcon();" class="btn btn-warning btn-sm">更換圖片</button>
        </div>
        <!-- END SIDEBAR USERPIC -->
        <!-- SIDEBAR USER TITLE -->
        <div class="profile-usertitle">
            <div class="profile-usertitle-name">
                {{ $user->name }}
            </div>
            <div class="profile-usertitle-job">
                {{ $constant_array['career']['value'][$user->career]}}
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
                <li>
                    <a href="/user/{{ $user->id }}" class="overview">
                        <i class="glyphicon glyphicon-home"></i>
                        總覽 </a>
                </li>
                @if( $status_array['is_self'] )
                    <li>
                        <a href="/user/{{ $user->id }}/edit" class="user-info">
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
                    <li>
                        <a href="/password/reset" class="user-password-reset">
                            <i class="glyphicon glyphicon-alert"></i>
                            重設密碼 </a>
                    </li>
                @endif
            </ul>
        </div>
        <!-- END MENU -->
    </div>

</div>

<form id="icon-upload-form" method="POST" action="{{ route('user.icon_update',$user->id) }}" enctype="multipart/form-data">

    <div class="modal fade" id="icon-modal" role="dialog">
    <div class="modal-dialog">

        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">上傳頭像</h4>
            </div>
            <div class="modal-body">

                    <div class="panel-body">
                        <div class="row">
                            <div class="col-xs-12 col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">
                                <div class="input-group image-preview">
                                    <input type="text" class="form-control image-preview-filename" disabled="disabled">
                                    <span class="input-group-btn">

                                            <button type="button" class="btn btn-default image-preview-clear" style="display:none;">
                                                <span class="glyphicon glyphicon-remove"></span> Clear
                                            </button>

                                        <div class="btn btn-default image-preview-input">
                                        <span class="glyphicon glyphicon-folder-open"></span>
                                        <span class="image-preview-input-title">Browse</span>
                                        <input type="file" accept="image/png, image/jpeg, image/gif" name="icon_image"/>
                                            <input type="hidden" value="{{ csrf_token() }}" name="_token">
                                         </div>
                                        </span>
                                </div>
                            </div>
                        </div>
                    </div>


            </div>
            <div class="modal-footer">
                <input type="submit" class="btn btn-primary" value="保存">
                <button type="button" class="btn btn-default" data-dismiss="modal">關閉</button>
            </div>
        </div>
    </div>
</div>
</form>