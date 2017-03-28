@extends('layouts.app')

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-md-6 col-md-offset-3 ">
                <div class="group-form-basic-content">
                    <h2>Group Approve Form</h2>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3>Basic information</h3>
                        </div>

                        <div class="panel-body">
                            <div class="basic-info">

                            </div>


                        </div>
                    </div>

                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3>Approve Information</h3>
                        </div>

                        <div class="panel-body">
                            <div class="approve-info">

                            </div>


                        </div>
                    </div>

                </div>

                <a class="btn btn-success" href="/group_form/$group_form->id /approve">Approve</a>
                <a class="btn btn-danger" style="right: 13px;position: absolute" href="/group_form/$group_form->id/reject">Reject</a>
            </div>
        </div>
    </div>

@endsection

@section('script')




@endsection







