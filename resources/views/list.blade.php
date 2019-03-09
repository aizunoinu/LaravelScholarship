@if (count($meisais) == 0)
    <p align="center">履歴が存在しません</p>
@else
    <div id="counter" align="right">
        {{$fitem}}-{{$litem}}/{{$mitem}}
    </div>
    <table align="center" border="1">
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
            <th>削除</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($meisais as $meisai)
            <tr id="item_{{$meisai->meisai_id}}">
                <td align="center">
                    <a href="search?name={{$name}}&email={{$email}}&title=詳細&searchID={{$meisai->meisai_id}}">
                        {{str_pad($meisai->meisai_id,4,0,STR_PAD_LEFT)}}</a></td>
                <td>{{$meisai->zankai}}回</td>
                <td>{{$meisai->zangaku}}</td>
                <td>{{date('Y年n月j日', strtotime($meisai->hikibi . '+0 day'))}}</td>
                <td>{{$meisai->hensaigaku}}</td>
                <td>{{$meisai->hensaimoto}}</td>
                <td>{{$meisai->suerisoku}}</td>
                <td>{{$meisai->risoku}}</td>
                <td>{{$meisai->hasu}}</td>
                <td>{{$meisai->atozangaku}}</td>
                <td align="center">
                    <a href="#" class="delete_button" title="{{$meisai->meisai_id}}">削除</a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <div id="links2">
        {{$meisais->appends(['email' => $email, 'name' => $name])->onEachSide(1)->links()}}
    </div>
@endif
