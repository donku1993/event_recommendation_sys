@extends('layouts.app')

@section('content')

    <div class="container">
        <div class="row">

            <form action="/participant/{{ $event->id }}/evaluation" method="POST">
                <input type="hidden" name="_method" value="PUT">
                <input type="hidden" name="page" value="">
                {{ csrf_field() }}
                <div class="col-md-12">
                    <table class="member-table table table-striped table-hover">
                        <thead>
                        <tr>
                            @if( $status_array['is_participant'] )
                                <th class="col-md-2">用戶頭像</th>
                                <th class="col-md-2">用戶名稱</th>
                            @else
                                <th class="col-md-2">用戶頭像</th>
                                <th class="col-md-2">用戶名稱</th>
                                <th style="text-align: center" class="col-md-3">評分</th>
                                <th style="text-align: center" class="col-md-5">評論</th>
                            @endif
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($participants as $participant)
                            <tr>
                                <td class="col-md-2">
                                    <img style="width: 50px;height: 50px;" src="{{ $participant->iconPath }}" alt="Usericon">
                                </td>
                                <td class="col-md-2" style="padding-top: 20px;">{{ $participant->name }}</td>
                                @if( $status_array['is_participant'] )

                                @else
                                    <td class="col-md-3">
                                        @if( !is_null($participant->pivot->grade_to_user) )
                                            <p style="text-align-last: center">{{ $constant_array['event_evaulation']['value'][$participant->pivot->grade_to_user] }}</p>
                                        @else
                                            <select style="text-align-last: center" class="form-control" name="grade_{{ $participant->id }}">
                                                <option class="placeholder" style="display: none" selected disabled value="0">未填寫</option>
                                                @foreach ($constant_array['event_evaulation']['value'] as $key => $value)
                                                    <option value="{{ $key }}">{{ $value }}</option>
                                                @endforeach
                                            </select>
                                        @endif

                                    </td>

                                    <td class="col-md-5">
                                        @if( !is_null($participant->pivot->grade_to_user) )
                                            @if(!is_null($participant->pivot->remark_to_user) )
                                                <p style="text-align-last: center">{{ $participant->pivot->remark_to_user }}</p>
                                            @else
                                                <p style="text-align-last: center">沒有評論。</p>
                                            @endif
                                        @else
                                            <input type="text" placeholder="輸入評論"  name="remark_{{ $participant->id }}" maxlength="60" class="form-control" >
                                        @endif
                                    </td>
                                @endif

                                
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <hr/>

                    {{ $participants->links() }}

                </div>

                <div class="col-md-6 col-lg-offset-3">
                    <input type="button" class="submit btn-lg btn-block btn-primary" value="提交">
                </div>

                <div class="modal fade" id="myModal" role="dialog">
                    <div class="modal-dialog">

                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h4 class="modal-title">提交評分</h4>
                            </div>
                            <div class="modal-body">
                                <strong>注意:提交後不能再更改評分和評論。</strong>
                                <p>確認提交嗎?</p>
                            </div>
                            <div class="modal-footer" >
                                <input  type="submit" class="pull-left confirm-submit btn btn-success" >
                                <button style="text-align: right" type="button" class="btn btn-danger" data-dismiss="modal">關閉</button>
                            </div>
                        </div>
                    </div>
                </div>

            </form>

        </div>


    </div>

@endsection

@section('script')
    <script>

        $('.submit').click(function () {
            $('#myModal').modal('show');
        });

    </script>
@endsection
