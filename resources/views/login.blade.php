@extends ('layouts.base')

<style>
    .error {
        color: red;
    }
    #msg {
        text-align: center;
        color: red;
        font-size: 16pt;
    }
</style>

@section ('title', 'ログイン画面')

@section ('content')
    <div id="login">
        <p id="msg">{{$msg}}</p>
        <form action="/login" method="post">
            {{ csrf_field() }}
            <table align="center">
                @if ($errors->has('email'))
                    <tr class="error" align="right"><th>※ERROR：</th><td align="left">{{$errors->first('email')}}</td></tr>
                @endif
                <tr align="right">
                    <td width="200">email（必須）：</td><td width="300" align="left"><input type="text" name="email" size="45" value="{{old('email')}}" required></td>
                </tr>
                <tr align="right">
                    <td width="200">password（必須）：</td><td width="300" align="left"><input type="password" name="password" size="45" value="{{old('password')}}" minlength="4" required></td>
                </tr>
            </table>
            <div id="menuField" align="center">
                <input class="menu_buttons" type="submit" value="ログイン">
            </div>
        </form>
    </div>
@endsection

@section ('footer')
    copyright 2019 Metaps-payment.
@endsection