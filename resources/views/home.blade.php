@extends('layouts.app')

@section('content')
    @include('layouts.search-template')
    <div class="container">
    <div class="row">

        <div class="col-md-18">
            <div class="form-group">
                <div class="row">
                    <div class="panel-body">
                        <a data-remote="true" href="javascript:void(0)" id="latest">
                            <h4 style=""><i class="glyphicon glyphicon-fire"></i> 最新活動</h4>
                        </a>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

@endsection

@section('script')
    <script type="text/javascript" src="/js/search.js"></script>
@endsection

@section('footer')
    @include('layouts.footer')
@endsection
