@extends ('layouts.base')

<style>
    #msg {
        text-align:center;
        color: red;
    }
    h1 {
        margin-top:10px;
        margin-bottom:.5rem
    }
</style>
@section ('title', 'ログインエラー')

@section ('content')
    <div id="error">
        @foreach ($msgs as $msg)
            <p id="msg">{{$msg}}</p>
        @endforeach
        <form action="/logout" method="post">
            {{ csrf_field() }}
            <div id="menuField" align="center">
                <input class="menu_buttons" type="submit" value="ログイン画面へ戻る">
            </div>
        </form>
    </div>
@endsection

@section ('footer')
    copyright 2019 Metaps-payment.
@endsection