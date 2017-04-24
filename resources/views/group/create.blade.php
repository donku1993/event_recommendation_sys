@extends('layouts.app')

@section('content')

    <style type="text/css">
        #registration-form-group fieldset:not(:first-of-type) {
            display: none;
        }
    </style>

    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">申請建立組織</div>
                    <div class="panel-body">

                        @if (count($errors) > 0)
                            <div class="alert alert-danger">
                                <strong>新增失敗</strong> 輸入不符合要求<br><br>
                                {!! implode('<br>', $errors->all()) !!}
                            </div>
                        @endif

                        <form id="registration-form-group" enctype="multipart/form-data">
                            {!! csrf_field() !!}
                            <div class="progress">
                                <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <fieldset>
                                <h2 class="stepTitle">第一步: 填寫組織基本資料</h2>
                                <div class="panel panel-primary">
                                    <div class="panel-heading">輸入組織名稱</div>
                                    <div class="panel-body">
                                        <input type="text" name="name" class="form-control"  id="content-name" placeholder="請輸入組織名稱">
                                    </div>
                                </div>
                                <br>

                                <div class="panel panel-success">
                                    <div class="panel-heading">輸入組織電郵</div>
                                    <div class="panel-body">
                                        <input type="email" name="email" class="form-control"  id="content-email" placeholder="請輸入組織電郵">
                                    </div>
                                </div>
                                <br>

                                <div class="panel panel-info">
                                    <div class="panel-heading">輸入組織聯絡電話</div>
                                    <div class="panel-body">
                                        <input type="text" name="phone" class="form-control"  id="content-phone" placeholder="請輸入組織電話">
                                    </div>
                                </div>
                                <br>

                                <div class="panel panel-warning">
                                    <div class="panel-heading">輸入組織地址</div>
                                    <div class="panel-body">
                                        <input type="text" name="address" class="form-control"  id="content-address" placeholder="請輸入組織地址">
                                    </div>
                                </div>
                                <br>

                                <div class="panel panel-danger">
                                    <div class="panel-heading">選擇活動常舉辦的地區</div>
                                    <div class="panel-body">
                                        @foreach($constant_array['location']['value'] as $key => $value)
                                            <label class="checkbox-inline">
                                                <input type="checkbox" name="activity_area_{{ $key }}" > {{ $value }}
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                                <br>


                                <div class="panel panel-info">
                                    <div class="panel-heading">輸入組織成立日期</div>
                                    <div class="panel-body">
                                        <strong>請選擇組織成立日期:</strong>
                                        <input type="text" name="establishment_date" id="establishment_dateBox" class="datepicker form-control" >
                                        <br>
                                    </div>
                                </div>
                                <br>

                                <div class="panel panel-success">
                                    <div class="panel-heading">輸入組織簡介</div>
                                    <div class="panel-body">
                                        <textarea  class="summernote" id="content" name="introduction"></textarea>
                                    </div>
                                </div>
                                <br>

                                <input type="button"  class="next btn btn-lg btn-primary" value="下一步" />

                            </fieldset>

                            <fieldset>
                                <h2>第二步: 填寫並上傳組織證明文件</h2>

                                <div class="panel panel-success">
                                    <div class="panel-heading">輸入組織註冊ID</div>
                                    <div class="panel-body">
                                        <input type="text" name="registered_id" class="form-control"  id="content-registered_id" placeholder="請輸入組織註冊ID">
                                    </div>
                                </div>
                                <br>

                                <div class="panel panel-warning">
                                    <div class="panel-heading">輸入組織創辦人名字</div>
                                    <div class="panel-body">
                                        <input type="text" name="principal_name" class="form-control"  id="content-principal_name" placeholder="輸入組織創辦人名字">
                                    </div>
                                </div>
                                <br>

                                <div class="panel panel-info">
                                    <div class="panel-heading">加入組織頭像</div>
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
                                        <span class="image-preview-input-title">Browse (上限2MB)</span>
                                        <input type="file" accept="image/png, image/jpeg, image/gif" name="icon_image"/>
                                            <input type="hidden" value="{{ csrf_token() }}" name="_token">
                                         </div>
                                        </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <br/><br/>

                                <div class="panel panel-danger">
                                    <div class="panel-heading">加入組織註冊文件</div>
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-xs-12 col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">
                                                <div class="input-group file">
                                                    <input type="text" class="form-control file-filename" disabled="disabled">
                                                    <span class="input-group-btn">

                                            <button type="button" class="btn btn-default file-clear" style="display:none;">
                                                <span class="glyphicon glyphicon-remove"></span> Clear
                                            </button>

                                        <div class="btn btn-default file-input">
                                        <span class="glyphicon glyphicon-folder-open"></span>
                                        <span class="file-input-title">Browse (上限2MB)</span>
                                        <input type="file"
                                               accept=".pdf, .csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel, text/plain, text/html, application/msword"
                                               name="registered_file"/>
                                            <input type="hidden" value="{{ csrf_token() }}" name="_token">
                                         </div>
                                        </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>



                                <input type="button"  class="previous btn btn-lg btn-danger" value="上一步" />
                                <input type="button"  class="submit btn btn-lg btn-success" value="提交申請" />

                            </fieldset>


                            <div class="modal fade" id="myModal" role="dialog">
                                <div class="modal-dialog">

                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title">Modal Header</h4>
                                        </div>
                                        <div class="modal-body">
                                            <p>Some text in the modal.</p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">關閉</button>
                                        </div>
                                    </div>
                                </div>
                            </div>


                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')

    <script type="text/javascript">
        uploadImage();
        uploadFile();
        $(document).ready(function() {
            $('.summernote').summernote({
                height:150,
            });
        });


        //ajax
        $(".submit").click(function () {

            $.ajax({
                url: "/group",
                type:"POST",
                dataType:'json',
                cache: false,
                data: new FormData($('#registration-form-group')[0]),
                processData: false,
                contentType: false,


                success: function(data){
                    if (data['message'] == "success"){
                        var InterValObj;
                        var count = 3;
                        var curCount;

                        $(".modal-title").text("递交成功");
                        $(".modal-body").html("<strong>递交申請成功!</strong>");

                        curCount = count;
                        InterValObj = window.setInterval(function () {
                            if (curCount == 0) {
                                window.clearInterval(InterValObj);
                                window.location = "/user/{{ Auth::user()->id }}/groups";
                            }
                            else {
                                curCount--;
                                $(".modal-title").text("递交成功");
                                $(".modal-body").html("<strong>递交申請成功!</strong><br>"+"系統會在" + curCount +"秒後轉到待批頁！");
                            }
                        }, 1000);

                        $('#myModal').modal('show');
                    }
                },

                error:function(data){
                    $(".modal-title").text("申請失敗");
                    $(".modal-body").text(data['responseText']);
                    $('#myModal').modal('show');
                }
            });
        });



        //progress bar

        $(document).ready(function(){
            var current = 1,current_step,next_step,steps;
            steps = $("fieldset").length;
            $(".next").click(function(){
                current_step = $(this).parent();
                next_step = $(this).parent().next();
                next_step.show();
                current_step.hide();
                setProgressBar(++current);
                $("html, body").animate({ scrollTop: 0 }, 400);
            });
            $(".previous").click(function(){
                current_step = $(this).parent();
                next_step = $(this).parent().prev();
                next_step.show();
                current_step.hide();
                setProgressBar(--current);
                $("html, body").animate({ scrollTop: 0 }, 400);
            });
            setProgressBar(current);
            // Change progress bar action
            function setProgressBar(curStep){
                var percent = parseFloat(100 / steps) * curStep;
                percent = percent.toFixed();
                $(".progress-bar")
                    .css("width",percent+"%")
                    .html(percent+"%");
            }
        });

        //date picker

        $(function(){

            $('#establishment_dateBox').datetimepicker({
                format:'Y/m/d',
                timepicker:false,
            });

        });

    </script>

@endsection







