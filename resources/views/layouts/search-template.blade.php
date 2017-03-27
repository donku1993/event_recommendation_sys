<div class="search-bar">
    <div class="container">
        <div class="row">
            <div class="col-md-20">
                <div class="panel panel-default">
                    <div class="panel-heading">

                        <form id="search_form_1" action="/event" method="GET" enctype="multipart/form-data">

                            <span  style="margin: 10px;">搜索活動好幫手</span>
                            <div class="dropdown" style="display: inline-block">
                                <button class="btn btn-default dropdown-toggle" type="button" id="menu1" data-toggle="dropdown">活動<span class="caret"></span></button>
                                <ul id="action-select" class="dropdown-menu" role="menu" aria-labelledby="menu1">
                                    <li class="event-select-text"><a href="javascript: return false;" >活動</a></li>
                                    <li class="group-select-text"><a href="javascript: return false;" >組織</a></li>
                                </ul>
                                {{--<input type="hidden" name="action" id="action" value="1">--}}
                            </div>

                            <div class="dropdown" style="width: 118px;height: 36px;display: inline-block ">
                                <button style="width: 118px;height: 36px;" class="btn btn-default dropdown-toggle" type="button" id="menu2" data-toggle="dropdown">地區<span class="caret"></span></button>
                                <ul id="location-select" class="dropdown-menu" role="menu" aria-labelledby="menu2">
                                    @foreach ($constant_array['location']['value'] as $key => $value)
                                        <li value= {{$key}}><a href="javascript: return false;" >{{ $value }}</a></li>
                                    @endforeach
                                </ul>
                                <input class="check-value" type="hidden" name="location" >
                            </div>

                            <div class="dropdown" style="width: 90px;height: 36px;display: inline-block">
                                <button style="width: 90px;height: 36px;" class="btn btn-default dropdown-toggle" type="button" id="menu5" data-toggle="dropdown">類型<span class="caret"></span></button>
                                <ul id="type-select" class="dropdown-menu" role="menu" aria-labelledby="menu5">
                                    @foreach ($constant_array['event_type']['value'] as $key => $value)
                                        <li value= {{$key}}><a href="javascript: return false;" >{{ $value }}</a></li>
                                    @endforeach
                                </ul>
                                <input class="check-value" type="hidden" name="type" >
                            </div>

                            <div class="startTimeBox" style="margin-left: 15px; display: inline-block; ">
                                <input  type="text" name="time_from" class="datepicker form-control check-value" id="startDateBox" placeholder="開始日期" >
                            </div>
                            <span>&nbsp;到&nbsp;</span>
                            <div class="endTimeBox" style="display: inline-block; border: none; border-radius: 6px; ">
                                <input  type="text" name="time_to" class="datepicker form-control check-value" id="endDateBox" placeholder="結束日期" >
                            </div>

                            <i class="glyphicon glyphicon-search" aria-hidden="true" style="left:25px;"></i>
                            <div class="searchBox" style="margin:0; display: inline-block; ">
                                <input type="text" name="event_name" class="searchName form-control check-value" placeholder="活動名稱"  style="padding-left: 25px">
                            </div>

                            <input type="submit"  id="searchBtn" class="btn btn-primary" value="搜索" />

                        </form>

                        <form id="search_form_2" action="/group" method="GET" enctype="multipart/form-data" style="display: none">
                            <span  style="margin: 40px;">查詢組織好幫手</span>

                            <div class="dropdown" style="display: inline-block">
                                <button class="btn btn-default dropdown-toggle" type="button" id="menu3" data-toggle="dropdown">組織<span class="caret"></span></button>
                                <ul id="action-select" class="dropdown-menu" role="menu" aria-labelledby="menu3">
                                    <li class="event-select-text"><a href="javascript: return false;" >活動</a></li>
                                    <li class="group-select-text"><a href="javascript: return false;" >組織</a></li>
                                </ul>
                                {{--<input type="hidden" name="action" id="action" value="2">--}}
                            </div>

                            <div class="dropdown" style="display: inline-block">
                                <button class="btn btn-default dropdown-toggle" type="button" id="menu4" data-toggle="dropdown">地區<span class="caret"></span></button>
                                <ul id="group-location-select" class="dropdown-menu" role="menu" aria-labelledby="menu4">
                                    @foreach ($constant_array['location']['value'] as $key => $value)
                                        <li value={{ $key }}><a href="javascript: return false;" >{{ $value }}</a></li>
                                    @endforeach
                                </ul>
                                <input type="hidden" name="activity_area" >
                            </div>

                            <i class="glyphicon glyphicon-search" aria-hidden="true" style="left:40px;"></i>
                            <div class="searchBox" style="margin-left: 15px; display: inline-block; border: none; border-radius: 6px; ">
                                <input type="text" name="group_name" class="searchName form-control" placeholder="組織名稱"  style="padding-left: 25px">
                            </div>

                            <input type="submit" class="btn btn-primary"  value="查詢" />

                        </form>


                    </div>
                </div>

            </div>

        </div>
    </div>
</div>
</div>