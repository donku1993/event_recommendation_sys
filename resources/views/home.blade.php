@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Dashboard</div>

                <div class="panel-body">

                </div>
            </div>
        </div>
    </div>
</div>

@include('layouts.search-template')
@endsection

@section('script')
    <script type="text/javascript" src="/js/search.js"></script>
@endsection
