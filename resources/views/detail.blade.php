@extends('layouts.base')

<style>
    .pagination {
        /*font-size: 15pt;*/
        text-align: center;
    }

    .pagination li {
        display: inline-block;
        border: 1px;
    }

    #showField #counter{
        text-align: left;
    }

    #showField #links1{
        margin-top: -45px;
        height: 45px;
        /*background-color: #9561e2;*/
    }

    #links {
        margin-top: 20px;
    }
    #showField{
        margin: 30px 100px 30px 100px;
    }
    #showField table{
        table-layout: auto;
        width: 100%;
    }
    #showField #col1 {
        background-color: #87CEEB;
        color: #000;
        text-align: center;
    }
</style>
@section('title', $title)

@section('content')
    <div id="showField">
        @if (count($items) == 0)
            <p align="center">該当データが存在しません。</p>
        @else
            <div id="counter" align="right">
                {{$fitem}}件-{{$litem}}件/{{$mitem}}件
            </div>
            <div id="links1" align="center">
                {{ $items->appends(['email' => $email, 'name' => $name, 'title' => $title, 'searchID' => $searchID, 'searchID2' => $searchID2,  'year' => $year, 'month' => $month, 'year2' => $year2, 'month2' => $month2, 'zankai' => $zankai, 'zankai2' => $zankai2])->onEachSide(1)->links() }}
            </div>
            <table align="center" border="1pt">
                <thead>
                <tr id="col1">
                    <th>明細ID</th>
                    <th>残り回数</th>
                    <th>残額</th>
                    <th>引落日</th>
                    <th>返済金額</th>
                    <th>返済元金</th>
                    <th>据置利息</th>
                    <th>利息</th>
                    <th>端数</th>
                    <th>引落後残額</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($items as $item)
                    <tr>
                        <td align="center">{{str_pad($item->meisai_id,4,0,STR_PAD_LEFT)}}</td>
                        <td>{{$item->zankai}}回</td>
                        <td>{{$item->zangaku}}</td>
                        <td>{{date('Y年n月j日', strtotime($item->hikibi . '+0 day'))}}</td>
                        <td>{{$item->hensaigaku}}</td>
                        <td>{{$item->hensaimoto}}</td>
                        <td>{{$item->suerisoku}}</td>
                        <td>{{$item->risoku}}</td>
                        <td>{{$item->hasu}}</td>
                        <td>{{$item->atozangaku}}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            <div id="links" align="center">
                {{ $items->appends(['email' => $email, 'name' => $name, 'title' => $title, 'searchID' => $searchID, 'searchID2' => $searchID2,  'year' => $year, 'month' => $month, 'year2' => $year2, 'month2' => $month2, 'zankai' => $zankai, 'zankai2' => $zankai2])->onEachSide(1)->links() }}
            </div>
        @endif
    </div>
    <div id="menuField">
        <table align="center">
            <tr>
                <td><input class="menu_buttons" type="button" value="戻る" onclick="history.back()"></td>
            </tr>
        </table>
    </div>
@endsection

@section ('footer')
    copyright 2019 Metaps-payment.
@endsection

