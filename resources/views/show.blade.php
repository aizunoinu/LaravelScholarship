@extends('layouts.base')

<style>
    #col1 {
        background-color: #87CEEB;
        color: #000;
        text-align: center;
    }

    .pagination {
        /*font-size: 15pt;*/
        text-align: center;
    }

    .pagination li {
        display: inline-block;
        /*background-color: #87CEEB;*/
        border: 1px;
    }

    #links {
        margin-top: 20px;
    }

    #searchField {
        position: relative;
        margin-top: 1em;
        margin-left: 155px;
        margin-bottom: 30px;
        padding: 0em 2em;

        width: 1100px;
        height: 150px;
        border: 1px solid #818182;
    }

    #searchField .caption {
        position: absolute;
        top: 0;
        left: 0;

        font-size: 1em;
        padding: 0 1em;
        margin: 0;
        background-color: #f8fafc;
        color: black;
        transform: translateY(-50%) translateX(1em);
        letter-spacing: 5px
    }

    #searchField table {
        margin-top: -20px;
        margin-left: 10px;
        width: 1000px;
        height: 120px;
        /*background-color: red;*/
    }

    #searchField #submit {
        margin-top: -20px;
    }

    #searchField .rows {
        margin-top: -20px;
        height: 10px;
    }
</style>
@section('title', "シミュレーション")

<script type="text/javascript">
    function submitAfterValidation(){
        var invalid = false;
        if (document.searchForm.searchID.value.length == 0
            && document.searchForm.zankai.value.length == 0
            && (document.searchForm.year.value.length == 0
                || document.searchForm.month.value.length == 0)){
                alert("検索条件が未入力です");
                return;
        }
        if (document.searchForm.searchID.value.length > 0){
            if(isNaN(document.searchForm.searchID.value)){
                alert('明細IDが数字ではありません')
                return;
            }
        }
        document.searchForm.submit();
    }
    // $("#searchForm").submit(function(){
    //     if($("input[searchID='searchID']").val() == ''){
    //         alert('入力してください');
    //         return false;
    //     }
    // })
</script>
@section('content')
    <section id="show">
        @if (count($items) == 0)
            <p align="center">履歴が存在しません</p>
        @else
            <div id="searchField">
                <h1 class="caption">検索条件</h1>
                <form name="searchForm" action="/login/search" method="post">
                    {{ csrf_field() }}
                    <input type="hidden" name="name" value="{{$name}}">
                    <input type="hidden" name="email" value="{{$email}}">
                    <table>
                        <tr class="rows">
                            <th width="80" style="text-align: right;">明細ID：</th>
                            <td width="200"><input size="10" type="text" name="searchID"></td>
                            <td width="50"></td>
                            <th width="80" style="text-align: right;">引落年月：</th>
                            <td width="200">
                                <select name="year">
                                    <option value="" selected>年</option>
                                    @for($i = (int)date('Y') - 20; $i <= (int)date('Y') + 20; $i++)
                                        <option value="{{$i}}">{{$i}}月</option>
                                    @endfor
                                </select>
                                <select name="month">
                                    <option value="" selected>月</option>
                                    @for($i = 1; $i <= 12; $i++)
                                        <option value="{{$i}}">{{$i}}月</option>
                                    @endfor
                                </select>
                            <td width="50"></td>
                            <th width="100" style="text-align: right;">残り回数：</th>
                            <td width="180"><input size="10" type="text" name="zankai">回</td>
                        </tr>
                    </table>
                    <div id="submit" align="center">
                        <input type="button" value="検索" onclick="submitAfterValidation()">
                        {{--<input type="submit" id="submit" value="検索">--}}
                    </div>
                </form>
            </div>
            <div id="showField">
                <table align="center" border="1">
                    <thead>
                    <tr id="col1">
                        <th width="80">明細ID</th>
                        <th width="80">残り回数</th>
                        <th width="120">残額</th>
                        <th width="140">引落日</th>
                        <th width="120">返済金額</th>
                        <th width="120">返済元金</th>
                        <th width="80">据置利息</th>
                        <th width="80">利息</th>
                        <th width="80">端数</th>
                        <th width="120">引落後残額</th>
                        <th width="80">削除</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($items as $item)
                        <tr>
                            <td width="80" align="center"><a href="detail?name={{$name}}&email={{$email}}&searchID={{$item->meisai_id}}">{{str_pad($item->meisai_id,4,0,STR_PAD_LEFT)}}</a>
                            </td>
                            <td width="80">{{$item->zankai}}</td>
                            <td width="120">{{$item->zangaku}}</td>
                            <td width="140">{{$item->hikibi}}</td>
                            <td width="120">{{$item->hensaigaku}}</td>
                            <td width="120">{{$item->hensaimoto}}</td>
                            <td width="80">{{$item->suerisoku}}</td>
                            <td width="80">{{$item->risoku}}</td>
                            <td width="80">{{$item->hasu}}</td>
                            <td width="120">{{$item->atozangaku}}</td>
                            <td width="80" align="center"><a href="del?name={{$name}}&email={{$email}}&searchID={{$item->meisai_id}}">削除</a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                <div id="links" align="center">
                    {{ $items->appends(['email' => $email, 'name' => $name])->onEachSide(1)->links() }}
                </div>
            </div>
        @endif
    </section>
    <section id="buttons">
        <table align="center">
            <tr>
                <input type="hidden" name="name" value="{{$name}}">
                <input type="hidden" name="email" value="{{$email}}">
                <td>
                    <input class="submit_button" type="button" value="CSV出力"
                           onclick="location.href='csv?name={{$name}}&email={{$email}}'">
                </td>
                <form action="/login" method="POST">
                    {{ csrf_field() }}
                    <input type="hidden" name="name" value="{{$name}}">
                    <input type="hidden" name="email" value="{{$email}}">
                    <td><input type="submit" value="メニューに戻る"></td>
                </form>
                <td>
                    <input class="submit_button" type="button" value="ログアウト" onclick="location.href='/login'">
                </td>
            </tr>
        </table>
    </section>
@endsection

@section ('footer')
    copyright 2019 Metaps-payment.
@endsection
