@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Dashboard</div>

                <div class="panel-body">
                	<form method="post" action="{{ route('user.icon', 1) }}" enctype="multipart/form-data">
                		{{ csrf_field() }}
                		<input type="hidden" name="_method" value="put">
                		<input type="file" name="icon_image">
                		<input type="submit" name="submit" value="upload">
                	</form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
