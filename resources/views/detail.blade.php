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

    #msg {
        text-align: center;
        color: red;
        margin-bottom: -10px;
    }

    #submit {
        margin-top: 10pt;
        text-align: center;
    }

    #addJokens {
        margin-top: 10px;
    }

    table {
        /*width: 960px;*/
        /*margin: 0 auto;*/
    }

    .table-header,
    .table-body {
        /*display: block;*/
        /*width: 100%;*/
    }

    .table-header {
        padding-right: 17px;
    }

    .table-body {
        /*height: 350px;*/
        /*overflow-y: scroll;*/
    }

    #content{
        margin-top: 15px;
    }
</style>
@section('title', "詳細")

@section('content')
    <div id="show">
        <table id="table" align="center" border="1pt">
            <thead class="table-header">
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
            </tr>
            </thead>
            <tbody class="table-body">
            @foreach ($items as $item)
                <tr>
                    <td width="80" align="center">{{str_pad($item->meisai_id,4,0,STR_PAD_LEFT)}}</td>
                    <td width="80">{{$item->zankai}}</td>
                    <td width="120">{{$item->zangaku}}</td>
                    <td width="140">{{$item->hikibi}}</td>
                    <td width="120">{{$item->hensaigaku}}</td>
                    <td width="120">{{$item->hensaimoto}}</td>
                    <td width="80">{{$item->suerisoku}}</td>
                    <td width="80">{{$item->risoku}}</td>
                    <td width="80">{{$item->hasu}}</td>
                    <td width="120">{{$item->atozangaku}}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
        </form>
    </div>
    <div id="content">
        <table align="center">
            <tr>
                <td><input type="button" value="一覧に戻る" onclick="history.back()"></td>
            </tr>
        </table>
    </div>
@endsection

@section ('footer')
    copyright 2019 Metaps-payment.
@endsection

