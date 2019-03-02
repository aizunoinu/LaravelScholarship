@extends ('layouts.base')

<style>
    .msg{
        color: red;
        text-align: right;
    }
    table#buttons{
        margin-top: 15pt;
    }
    tr.rows{
        text-align: right;
    }
</style>
@section ('title', '設定')

@section ('content')
    <div id="content">
        {{--<p>{{$email}}</p>--}}
        <form action="/login/create" method="post">
            {{ csrf_field() }}
            <input type="hidden" name="title" value="シミュレーション">
            @if(isset($name))
                <input type="hidden" name="name" value={{$name}}>
                <input type="hidden" name="email" value="{{$email}}">
            @else
                <input type="hidden" name="name" value={{old('name')}}>
                <input type="hidden" name="email" value="{{old('email')}}">
            @endif
            <table align="center">
                @if ($errors->has('goukei'))
                    <tr class="msg"><th>ERROR：</th><td align="left">{{$errors->first('goukei')}}</td></tr>
                @endif
                <tr class="rows">
                    <td width="140">借用金額：</td>
                    <td align="left" width="250"><input type="text" name="goukei" size="15" value="{{old('goukei')}}"> 円</td>
                </tr>
                @if ($errors->has('finyear'))
                    <tr class="msg"><th>ERROR：</th><td align="left">{{$errors->first('finyear')}}</td></tr>
                @endif
                <tr class="rows">
                    <td width="140">借用終了年：</td>
                    <td align="left" width="250"><input type="text" name="finyear" size="15" value="{{old('finyear')}}"> 年</td>
                </tr>
                @if ($errors->has('finmonth'))
                    <tr class="msg"><th>ERROR：</th><td align="left">{{$errors->first('finmonth')}}</td></tr>
                @endif
                <tr class="rows">
                    <td width="140">借用終了月：</td>
                    <td align="left" width="250"><input type="text" name="finmonth" size="15" value="{{old('finmonth')}}"> 月</td>
                </tr>
                @if ($errors->has('nenri'))
                    <tr class="msg"><th align="right">ERROR：</th><td align="left">{{$errors->first('nenri')}}</td></tr>
                @endif
                <tr class="rows">
                    <td width="140">年利：</td>
                    <td align="left" width="250"><input type="text" name="nenri" size="15" value="{{old('nenri')}}"> %</td>
                </tr>
            </table>
            <table id="buttons" align="center">
                <td><input type="submit" name="act1" value="シミュレーション開始"></td>
                <td><input type="button" name="act2" value="前に戻る" onclick="history.back()"></td>
            </table>
        </form>
    </div>
@endsection

@section ('footer')
    copyright 2019 Metaps-payment.
@endsection
