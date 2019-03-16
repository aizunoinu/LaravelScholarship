<?php

namespace App\Http\Controllers;

//use Illuminate\Http\Request;
use Illuminate\Support\Facades\Request;
use Illuminate\Http\Response;
use App\User;

class Show extends Controller
{
    /**
     * 検索条件に該当するデータを取得する
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
    public function index(){

        // セッションにユーザーデータが無ければLogin画面を表示
        if(!session()->has('user')){
            return redirect('login');
        }
        // セッションを取得
        $session_data = session()->get('user');

        // ビューからパラメタを受け取る。
        $data = Request::all();

        // emailからUserを取得する。
        $user = User::where('email', $session_data['email'])->first();

        // Queryの基本形を作成
        $query = $user->meisais();

        $maxID = $user->meisais()->max('meisai_id');
        $minID = $user->meisais()->min('meisai_id');
        $minDate = date('1900-01-01 00:00:00');
        $maxDate = date('2100-01-31 00:00:00');
        $minZankai = 0;
        $maxZankai = 240;

        // 明細IDによる検索条件を追加
        if(isset($data['searchID'])){
            $minID = (int)$data['searchID'];
            $maxID = (int)$data['searchID'];
        }

        // 明細IDによる検索条件を追加
        if(isset($data['searchID2'])){
            $maxID = (int)$data['searchID2'];
        }

        // 引落年月による検索条件を追加
        if (isset($data['year'])){
            $minDate = date('Y-m-d H:i:s', strtotime($data['year'] . "-" . $data['month'] . "-" . "1" . '+0 month'));
            $maxDate = date('Y-m-d H:i:s', strtotime($data['year'] . "-" . $data['month'] . "-" . "31" . '+0 month'));
        }

        // 引落年月による検索条件を追加
        if (isset($data['year2'])){
            $maxDate = date('Y-m-d H:i:s', strtotime($data['year2'] . "-" . $data['year2'] . "-" . "31" . '+0 month'));
        }

        // 残り回数による検索条件を追加
        if (isset($data['zankai'])){
            $minZankai = $data['zankai'];
            $maxZankai = $data['zankai'];
        }

        // 残り回数による検索条件を追加
        if (isset($data['zankai2'])){
            $maxZankai = $data['zankai2'];
        }

        $data['meisais'] = $meisais = $query
            ->moreThanID($minID)
            ->lessThanID($maxID)
            ->moreThanHikibi($minDate)
            ->lessThanHikibi($maxDate)
            ->moreThanZankai($minZankai)
            ->LessThanZankai($maxZankai)
            ->paginate(15);

        $data['first_item_num'] = $meisais->firstItem();
        $data['last_item_num'] = $meisais->lastItem();
        $data['total_item_num'] = $user->meisais()->count();

        return view('show', $data);
    }

    /**
     * 削除ボタンが押下されたときに明細を削除する。
     *
     * @param Request $request
     * @return void
     */
    public function ajaxDelete(Request $request){
        // emailからUserを取得する。
        $user = User::where('email', $request->email)->first();

        // Userの指定された明細IDのレコードを削除
        $user->meisais()->where('meisai_id', $request->searchID)->delete();
    }

    /**
     * meisaisテーブルの情報をCSVに吐き出す
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function csv(Request $request){
        $fileName = '明細' . '.csv';

        $csvFileName = "/Users/junya_sato/Downloads/" . $fileName;

        $res = fopen($csvFileName, 'w');

        if ($res === FALSE) throw new Exception('ファイルの書き込みに失敗しました。');

        $csvHeader = array('明細ID', '残り回数', '残額', '引落日', '返済金額', '返済元金', '据置利息', '利息', '端数', '引落後残額');

        fputcsv($res, $csvHeader);

        $user = User::where('email', $request->email)->first();

        // 明細のIDが指定されているとき
        if (isset($request->searchID)) {
            $maxID = $request->searchID;
            $minID = $request->searchID;
        } else {
            $maxID = $user->meisais()->max('meisai_id');
            $minID = $user->meisais()->min('meisai_id');
        }

        $meisais = $user->meisais()->moreThanID($minID)->lessThanID($maxID)->get();

        foreach ($meisais as $meisai) {
            fputcsv($res, [str_pad($meisai->meisai_id,4,0,STR_PAD_LEFT), $meisai->zankai . '回' , $meisai->zangaku, date('Y年n月j日', strtotime($meisai->hikibi . '+0 day')), $meisai->hensaigaku, $meisai->hensaimoto, $meisai->suerisoku, $meisai->risoku, $meisai->hasu, $meisai->atozangaku,]);
        }

        fclose($res);

        return Response::download($csvFileName, $fileName);
    }

    public function viewPreSet(Request $request){

        $user = User::where('email', $request->email)->first();

        $meisais = $user->meisais()->orderBy('meisai_id', 'asc')->get();

        foreach ($meisais as $meisai){
            $years[date('Y', strtotime($meisai->hikibi . '+0 day'))] = date('Y', strtotime($meisai->hikibi . '+0 day'));
        }

        return view('prepayset', [
            'name' => $request->name,
            'email' => $request->email,
            'years' => $years,
            'msg' => '奨学金の残額は ' . $meisais[0]->zangaku . ' です',
        ]);
    }

    public function redirectMenu(){
        return redirect('/login');
    }
}
