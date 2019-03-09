@extends ('layouts.base')

<style>
    .rows {
        text-align: right;
    }
</style>

@section ('title', '設定')

@section ('content')
    <div id="content">
        <form action="/login/create" method="post">
            {{ csrf_field() }}
            <input type="hidden" name="name" value={{$name}}>
            <input type="hidden" name="email" value="{{$email}}">
            <input type="hidden" name="title" value="シミュレーション">
            <table align="center">
                <tr class="rows">
                    <td width="140">借用金額：</td>
                    <td align="left" width="250"><input type="number" min="0" name="goukei" size="15" required>円</td>
                </tr>
                <tr class="rows">
                    <td width="140">借用終了年：</td>
                    <td align="left" width="250"><input type="number" min="0" name="finyear" size="15" required>年</td>
                </tr>
                <tr class="rows">
                    <td width="140">借用終了月：</td>
                    <td align="left" width="250"><input type="number" min="0" name="finmonth" size="15" required>月</td>
                </tr>
                <tr class="rows">
                    <td width="140">年利：</td>
                    <td align="left" width="250"><input type="number" step="0.0001" min="0" name="nenri" size="15" required>%</td>
                </tr>
            </table>
            <div id="menuField">
                <table align="center">
                    <td><input class="menu_buttons" type="button" name="act2" value="前に戻る" onclick="history.back()"></td>
                    <td><input class="menu_buttons" type="submit" name="act1" value="シミュレーション開始"></td>
                </table>

            </div>
        </form>
    </div>
@endsection

@section ('footer')
    copyright 2019 Metaps-payment.
@endsection
