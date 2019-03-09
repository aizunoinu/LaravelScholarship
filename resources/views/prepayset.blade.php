@extends ('layouts.base')

<style>
    #msg {
        color: red;
        text-align: center;
    }

    tr .rows {
        text-align: right;
    }

    #result{
        margin-top: 30px;
    }
</style>
@section ('title', '繰上げ設定')

@section ('script')
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/2.1.0/jquery.min.js"></script>
    <script>
        function maxLengthCheck(object) {
            if (object.value.length > object.maxLength) {
                object.value = object.value.slice(0, object.maxLength);
            }
        }

        // 開始ボタン押下
        $(function () {
            $('#start_button').on('click', function () {
                if($('#kingaku').val() > 0){
                    $.ajax({
                        url: '/login/ajax_prepay',
                        type: 'get',
                        data: {
                            name: '{{$name}}',
                            email: '{{$email}}',
                            year: $('#year').val(),
                            month: $('#month').val(),
                            kingaku: $('#kingaku').val(),
                        },
                    })
                    // Ajaxリクエストが成功した場合
                        .done(function (data) {
                            console.log('ajaxが正常終了しました。')
                            $('#result').html(data);
                            // location.reload();
                        })
                        // Ajaxリクエストが失敗した場合
                        .fail(function (data) {
                            console.log('ajaxが異常終了しました')
                        });
                    return true;
                }
                alert('金額が入力されていません。')
                return false;
            });
        });
    </script>
@endsection

@section ('content')
    <div id="content">
        <div id="msg" align="center">
            <p>{{$msg}}</p>
        </div>
        <table align="center">
            <tr class="rows">
                <td width="140">繰上げ実施年：</td>
                <td align="left" width="150">
                    <select id="year" style="width: 100px;">
                        @foreach($years as $yearK => $yearV)
                            <option value="{{$yearV}}">{{$yearV}}月</option>
                        @endforeach
                    </select>
                </td>
            </tr>
            <tr class="rows">
                <td width="140">繰上げ実施月：</td>
                <td align="left" width="150">
                    <select id="month" style="width: 100px;">
                        @for($i = 1; $i <= 12; $i++)
                            <option value="{{$i}}">{{$i}}月</option>
                        @endfor
                    </select>
                </td>
            </tr>
            <tr class="rows">
                <td width="140">繰上げ実施金額：</td>
                <td align="left" width="150"><input type="number" id="kingaku" style="width: 100px;" min="0"
                                                    maxlength="8" minlength="1" oninput="maxLengthCheck(this)"
                                                    required> 円
                </td>
            </tr>
        </table>
        <table id="menuField" align="center">
            <td><input class="menu_buttons" type="button" name="act2" value="前に戻る" onclick="history.back()"></td>
            <td><input id="start_button" class="menu_buttons" type="button" name="act1" value="開始"></td>
        </table>
    </div>
    <div id="result">

    </div>
@endsection

@section ('footer')
    copyright 2019 Metaps-payment.
@endsection
