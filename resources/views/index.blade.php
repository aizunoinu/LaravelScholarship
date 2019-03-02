@extends ('layouts.base')

<style>
    #logout {
        margin-top:50px;
        text-align: center;
    }
    /*table{*/
        /*font-size: 10pt;*/
    /*}*/
    #submit_button1{
        margin-top: 15pt;
    }
    #submit_button2{
        margin-top: 15pt;
    }
    #submit_button3{
        margin-top: 15pt;
        text-align: center;
    }
    #msg{
        font-size: 20pt;
    }
</style>
@section ('title', 'MENU')

@section ('content')
    <div id="content">
        <table align="center" border="1pt">
            <tr>
                <th align="left" width="200">新規シミュレーション</th>
                <td align="center" valign="middle" width="200">
                    <form action="/login/setting" method="POST">
                        {{ csrf_field() }}
                        <input type="hidden" name="name" value="{{$name}}">
                        <input type="hidden" name="email" value="{{$email}}">
                        <input id="submit_button1" type="submit" value="新規シミュレーション">
                    </form>
                </td>
            </tr>
            <tr>
                <th align="left">履歴から復元</th>
                <td align="center" valign="middle" width="200">
                    <form action="/login/show" method="POST">
                        {{ csrf_field() }}
                        <input type="hidden" name="name" value="{{$name}}">
                        <input type="hidden" name="email" value="{{$email}}">
                        <input type="hidden" name="title" value="シミュレーション">
                        <input id="submit_button2" type="submit" value="履歴から復元">
                    </form>
                </td>
            </tr>
        </table>
    </div>
    <div id="submit_button3">
        <input type="button" value="ログアウト" onclick="location.href='/login'">
    </div>
@endsection

@section ('footer')
    copyright 2019 Metaps-payment.
@endsection
