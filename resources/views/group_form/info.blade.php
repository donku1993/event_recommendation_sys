@extends('layouts.app')

@section('content')

    <form method="POST"  enctype="multipart/form-data" class="form-horizontal" role="form">

        <div class="container">
            <div class="row">
                <div class="col-md-6 col-md-offset-3 ">
                    <div class="group-form-basic-content">
                        <h2>Group Approve Form # {{ $group_form->id }}</h2>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <a data-toggle="collapse" href="#collapse1">
                                    <h3>Basic information</h3>
                                </a>
                            </div>

                            <div id="collapse1" class="panel-collapse collapse">
                                <div class="panel-body">
                                    <div class="basic-info">
                                        <img src="{{ $group_form->iconPath }}" alt="Group_icon" class="col-md-offset-4" style="height: 150px;width: 150px;">

                                        <div class="col-md-12 form-group">
                                            <hr>
                                            <label class="control-label" for="establishment_date">Establishment Date:</label>
                                            <p class="form-horizontal form-control-static">{{ $group_form->establishment_date->toDateString() }}</p>
                                            <label class="control-label" for="name">Name:</label>
                                            <p class="form-horizontal form-control-static">{{ $group_form->name }}</p>
                                            <label class="control-label" for="email">Email:</label>
                                            <p class="form-horizontal form-control-static">{{ $group_form->email }}</p>
                                            <label class="control-label" for="phone">Phone:</label>
                                            <p class="form-horizontal form-control-static">{{ $group_form->phone }}</p>
                                            <label class="control-label" for="address">Address:</label>
                                            <p class="form-horizontal form-control-static">{{ $group_form->address }}</p>
                                            <label class="control-label" for="introduction">Introduction:</label>
                                            <p class="form-horizontal form-control-static">{{ $group_form->introduction }}</p>
                                        </div>

                                    </div>


                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="group-form-approve-content">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <a data-toggle="collapse" href="#collapse2">
                                    <h3>Approve Information</h3>
                                </a>
                            </div>

                            <div id="collapse2" class="panel-collapse collapse">
                                <div class="panel-body">
                                    <div class="approve-info">

                                        <div class="col-md-12 form-group">
                                            <label class="control-label" for="principal_name">Principal Name:</label>
                                            <p class="form-horizontal form-control-static">{{ $group_form->principal_name }}</p>
                                            <label class="control-label" for="registered_id">Registered id:</label>
                                            <p class="form-horizontal form-control-static">{{ $group_form->registered_id }}</p>
                                            <label class="control-label" for="registered_file">Registered File:</label>
                                            <a href="{{ $group_form->registered_file }}" download="registered_file">
                                                <p class="form-horizontal form-control-static">Registered_File_From_Group_{{ $group_form->id }}</p>
                                            </a>

                                        </div>

                                    </div>


                                </div>
                            </div>


                        </div>
                    </div>

                    <div class="group-form-remark " style="margin-bottom: 30px">
                        <label for="remark">Remark:</label>
                        <textarea name="remark" class="form-control" rows="4" placeholder="Enter the remark for the group as suggestion."></textarea>
                    </div>


                    <a class="btn btn-success" href="/group_form/{{ $group_form->id  }}/approve">Approve</a>
                    <a class="btn btn-danger" style="right: 13px;position: absolute" href="/group_form/{{ $group_form->id }}/reject">Reject</a>
                </div>
            </div>
        </div>

    </form>>

@endsection

@section('script')




@endsection







