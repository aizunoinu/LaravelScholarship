@extends ('layouts.base')

<style>
    #contentsField{
        /*background-color: #2fa360;*/
    }

    #contentsField table {
        table-layout: auto;
        font-size: 10pt;
        height: 15%;
    }

    #contentsField .action_buttons{
        border-radius: 10px;
        background-color: #2fa360;
        width: 200px;
        height: 40px;
        font-weight: bold;
        color: white;
    }

    #contentsField .action_buttons:hover{
        background-color: #2fc360;
    }
</style>
@section ('title', 'MENU')

@section ('content')
    <div id="contentsField">
        <table border="1" align="center">
            <tr>
                <th style="text-align: center;" width="200">新規シミュレーション</th>
                <form action="/login/set" method="POST">
                    {{ csrf_field() }}
                    <input type="hidden" name="name" value="{{$name}}">
                    <input type="hidden" name="email" value="{{$email}}">
                    <td align="center" width="230">
                        <input class="action_buttons" type="submit" value="新規シミュレーション">
                    </td>
                </form>
            </tr>
            <tr>
                <th style="text-align: center;">履歴から表示</th>
                <form action="/login/show" method="get">
                    <input type="hidden" name="name" value="{{$name}}">
                    <input type="hidden" name="email" value="{{$email}}">
                    <td align="center" width="230">
                        <input class="action_buttons" type="submit" value="履歴から表示">
                    </td>
                </form>
            </tr>
        </table>
    </div>
    <div id="menuField" align="center">
        <input class="menu_buttons" type="button" value="ログアウト" onclick="location.href='/login'">
    </div>
@endsection

@section ('footer')
    copyright 2019 Metaps-payment.
@endsection
