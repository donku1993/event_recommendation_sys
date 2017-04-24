<div class="search-bar">
    <div class="container">
        <div class="row">
            <div class="col-md-20">
                <div class="panel panel-default">
                    <div class="panel-heading" style="padding-top: 0px;height: 70.95px">


                        <form id="search_form_1" action="/group" method="GET" enctype="multipart/form-data">

                            <span  style="margin: 10px;">查詢組織好幫手</span>
                            <div class="dropdown" style="display: inline-block">
                                <button class="btn btn-default dropdown-toggle" type="button" id="menu3" data-toggle="dropdown">組織<span class="caret"></span></button>
                                <ul id="action-select" class="dropdown-menu" role="menu" aria-labelledby="menu3">
                                    <li class="event-select-text"><a href="javascript: return false;" >活動</a></li>
                                    <li class="group-select-text"><a href="javascript: return false;" >組織</a></li>
                                </ul>
                                {{--<input type="hidden" name="action" id="action" value="2">--}}
                            </div>

                            <div class="dropdown" style="width: 118px;height: 36px;display: inline-block ">
                                <button style="width: 118px;height: 36px;" class="btn btn-default dropdown-toggle" type="button" id="menu4" data-toggle="dropdown">地區<span class="caret"></span></button>
                                <ul id="group-location-select" class="dropdown-menu" role="menu" aria-labelledby="menu4">
                                    @foreach ($constant_array['location']['value'] as $key => $value)
                                        <li value= {{$key}}><a href="javascript: return false;" >{{ $value }}</a></li>
                                    @endforeach
                                </ul>
                                <input class="check-value" type="hidden" name="activity_area" >
                            </div>


                            <div class="searchBox" style="margin:0; display: inline-block; ">
                                <i class="glyphicon glyphicon-search" aria-hidden="true" style="left:5px; top:30px;"></i>
                                <input type="text" name="group_name" style="display: inline-block; padding-left: 25px;" class="searchName form-control check-value" placeholder="組織名稱" >
                            </div>

                            <input type="submit"  id="searchBtn" class="btn btn-primary" value="查詢" />

                        </form>

                    </div>

                </div>
            </div>

        </div>

    </div>
</div>
