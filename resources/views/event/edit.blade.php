@extends('layouts.app')

@section('content')
    <style type="text/css">
        #registration-form-event fieldset:not(:first-of-type) {
            display: none;
        }
    </style>

    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">修改活動</div>
                    <div class="panel-body">

                        @if (count($errors) > 0)
                            <div class="alert alert-danger">
                                <strong>新增失敗</strong> 輸入不符合要求<br><br>
                                {!! implode('<br>', $errors->all()) !!}
                            </div>
                        @endif

                        <form id="registration-form-event" enctype="multipart/form-data">
                            <input type="hidden" name="_method" value="PUT">
                            {!! csrf_field() !!}
                            <div class="progress">
                                <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <fieldset>
                                <h2 class="stepTitle">第一步: 活動基本資料</h2>
                                <div class="panel panel-primary">
                                    <div class="panel-heading">加入活動標題</div>
                                    <div class="panel-body">
                                        <input type="text" value="{{ $event->title }}" name="title" class="check-value form-control"  id="title" placeholder="請輸入標題">
                                    </div>
                                </div>
                                <br>


                                <div class="panel panel-warning">
                                    <div class="panel-heading">選擇活動類型</div>
                                    <div class="panel-body">
                                        <select class="check-value form-control" name="type">
                                            @foreach ($constant_array['event_type']['value'] as $key => $value)
                                                @if( $event->type == $key)
                                                    <option selected value="{{ $key }}">{{ $value }}</option>
                                                @else
                                                    <option value="{{ $key }}">{{ $value }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <br><br>


                                <div class="panel panel-success">
                                    <div class="panel-heading">加入活動起始時間</div>
                                    <div class="panel-body">
                                        <strong>請輸入活動開始日期:</strong>
                                        <input  type="text" value="{{ $event->startDate }}" id="startDateBox" name="startDate" class="check-value datepicker form-control">
                                        <br>
                                    </div>
                                </div>

                                <br><br>

                                <div class="panel panel-danger">
                                    <div class="panel-heading">加入活動結束時間</div>
                                    <div class="panel-body">
                                        <strong>請輸入活動結束日期:</strong>
                                        <input type="text" value="{{ $event->endDate }}" name="endDate" id="endDateBox" class="check-value datepicker form-control" >
                                        <br>
                                    </div>
                                </div>


                                <br><br>

                                <div class="panel panel-info">
                                    <div class="panel-heading">加入報名截止時間</div>
                                    <div class="panel-body">
                                        <strong>請輸入報名截止日期:</strong>
                                        <input type="text" value="{{ $event->signUpEndDate }}" name="signUpEndDate" id="signUpEndDateBox" class="check-value datepicker form-control">
                                        <br>
                                    </div>
                                </div>


                                <br><br>

                                <div class="panel panel-warning">
                                    <div class="panel-heading">加入活動人數</div>
                                    <div class="panel-body">
                                        <input type="number" value="{{ $event->numberOfPeople }}" min="1" id="numPeople" name="numberOfPeople" class="check-value form-control" placeholder="請輸入人數" >
                                    </div>
                                </div>


                                <br><br>

                                <div class="panel panel-info">
                                    <div class="panel-heading">加入活動預覽圖</div>
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
                                        <input type="file" accept="image/png, image/jpeg, image/gif" name="previewImage"/>
                                            <input type="hidden" value="{{ csrf_token() }}" name="_token">
                                         </div>
                                        </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <br><br>

                                <div class="panel panel-success">
                                    <div class="panel-heading">選擇活動區域</div>
                                    <div class="panel-body">
                                        <select class="check-value form-control" name="location">
                                            @foreach ($constant_array['location']['value'] as $key => $value)
                                                @if( $event->location == $key)
                                                    <option selected value="{{ $key }}">{{ $value }}</option>
                                                @else
                                                    <option value="{{ $key }}">{{ $value }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <input type="button"  class="next btn btn-lg btn-primary" value="下一步" />

                            </fieldset>

                            <fieldset>
                                <h2>第二步: 活動內容</h2>
                                <div class="panel panel-success">
                                    <div class="panel-heading">加入活動內容</div>
                                    <div class="panel-body">
                                        <textarea class="check-value summernote" id="content" name="content">{!! $event->content !!}</textarea>
                                    </div>
                                </div>

                                <div class="panel panel-warning">
                                    <div class="panel-heading">加入活動流程</div>
                                    <div class="panel-body">
                                        <textarea  class="check-value summernote" id="schedule" name="schedule">{{ $event->schedule }}</textarea>
                                    </div>
                                </div>

                                <div class="panel panel-danger">
                                    <div class="panel-heading">加入活動需求</div>
                                    <div class="panel-body">
                                        <textarea  class="check-value summernote" id="requirement" name="requirement">{{ $event->requirement }}</textarea>
                                    </div>
                                </div>

                                <div class="panel panel-info">
                                    <div class="panel-heading">加入備註事項</div>
                                    <div class="panel-body">
                                        <textarea  class="check-value summernote" id="remark" name="remark">{{ $event->remark }}</textarea>
                                    </div>
                                </div>

                                <input type="button"  class="previous btn btn-lg btn-danger" value="上一步" />
                                <input type="button"  class="next btn btn-lg btn-primary" value="下一步" />

                            </fieldset>

                            <fieldset>
                                <h2>第三步: 填選活動相關的技能和興趣(可選)</h2>
                                <div class="panel panel-success">
                                    <div class="panel-heading">填選活動相關的技能和興趣</div>
                                    <div class="panel-body">

                                        @foreach ($constant_array['interest_skills']['value'] as $key => $value)
                                            <label class="checkbox-inline">
                                                @if( $event->bonus_skills[$key])
                                                    <input checked type="checkbox" name="bonus_skills_{{ $key }}" value="true"/>{{ $value }}
                                                @else
                                                    <input type="checkbox" name="bonus_skills_{{ $key }}" value="true"/>{{ $value }}
                                                @endif
                                            </label>
                                        @endforeach

                                    </div>
                                </div>
                                <input type="button"  class="previous btn btn-lg btn-danger" value="上一步" />
                                <input type="button"  class="submit btn btn-lg btn-success" value="確認修改" />

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

        $(document).ready(function() {
            $('.summernote').summernote({
                height:150,
            });
        });

        //ajax
        $(".submit").click(function () {

            $.ajax({
                url: "/event/{{ $event->id }}",
                type:"POST",
                dataType:'json',
                cache: false,
                data: new FormData($('#registration-form-event')[0]),
                processData: false,
                contentType: false,

                success: function(data){
                    if (data['message'] == "success"){

                        var InterValObj;
                        var count = 3;
                        var curCount;

                        $(".modal-title").text("修改成功");
                        $(".modal-body").html("<strong>活動修改成功!</strong>");

                        curCount = count;
                        InterValObj = window.setInterval(function () {
                            if (curCount == 0) {
                                window.clearInterval(InterValObj);
                                window.location = "/user/{{ Auth::user()->id }}/events";
                            }
                            else {
                                curCount--;
                                $(".modal-title").text("活動修改成功");
                                $(".modal-body").html("<strong>活動修改成功!</strong><br>"+"系統會在" + curCount +"秒後跳到首頁！");
                            }
                        }, 1000);

                        $('#myModal').modal('show');
                    }


                },

                error:function(data){
                    console.log(data['responseText']);
                    $(".modal-title").text("修改失敗");
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
            var tomorrow = new Date();
            tomorrow.setDate(tomorrow.getDate() + 1);

            $('#startDateBox').datetimepicker({
                format:'Y/m/d H:i',
                minDate: tomorrow,
                onShow:function( ct ){
                    this.setOptions({
                        maxDate:$('#endDateBox').val()?$('#endDateBox').val():false
                    })
                }
            });

            $('#endDateBox').datetimepicker({
                format:'Y/m/d H:i',
                onShow:function( ct ){
                    this.setOptions({
                        minDate:$('#startDateBox').val()?$('#startDateBox').val():false
                    })
                }
            });

            $('#signUpEndDateBox').datetimepicker({
                format:'Y/m/d H:i',
                minDate: tomorrow,
                onShow:function( ct ){
                    this.setOptions({
                        maxDate:$('#startDateBox').val()?$('#startDateBox').val():false
                    })
                }
            });
        });

    </script>

@endsection







