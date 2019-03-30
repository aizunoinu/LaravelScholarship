<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\User;

class Show extends Controller
{
    /**
     * 検索条件に該当するデータを取得する
     * @param $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
    public function index(Request $request){

        // セッションにユーザー情報が無ければLogin画面を表示
        if(!session()->has('user')){
            return redirect('login');
        }

        // セッションからユーザー情報を取得
        $user = session()->get('user');

        // リクエストを受け取る。
        $data = $request->all();

        // 検索条件クリアボタンが謳歌された時
        if(isset($data['searchClearButton'])){
            $data['searchID'] = null;
            $data['searchID2'] = null;
            $data['year'] = null;
            $data['month'] = null;
            $data['year2'] = null;
            $data['month2'] = null;
            $data['zankai'] = null;
            $data['zankai2'] = null;
        }

        // Queryの基本形を作成
        $query = $user->meisais();

        // 明細IDによる検索条件を追加
        if(isset($data['searchID'])){
            $query = $query->moreThanID($data['searchID']);
            $query = $query->lessThanID($data['searchID']);
        }

        // 明細IDによる検索条件を追加
        if(isset($data['searchID2'])){
            $query = $query->lessThanID($data['searchID2']);
        }

        // 引落年月による検索条件を追加
        if (isset($data['year'])){
            $query = $query->moreThanHikibi(date('Y-m-d H:i:s', strtotime($data['year'] . "-" . $data['month'] . "-" . "1" . '+0 month')));
            $query = $query->lessThanHikibi(date('Y-m-d H:i:s', strtotime($data['year'] . "-" . $data['month'] . "-" . "31" . '+0 month')));
        }

        // 引落年月による検索条件を追加
        if (isset($data['year2'])){
            $query = $query->lessThanHikibi(date('Y-m-d H:i:s', strtotime($data['year2'] . "-" . $data['month2'] . "-" . "31" . '+0 month')));
        }

        // 残り回数による検索条件を追加
        if (isset($data['zankai'])){
            $query = $query->moreThanZankai($data['zankai']);
            $query = $query->lessThanZankai($data['zankai']);
        }

        // 残り回数による検索条件を追加
        if (isset($data['zankai2'])){
            $query = $query->moreThanZankai($data['zankai2']);
        }

        $data['meisais'] = $meisais = $query->paginate(15);

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

        // リクエストの取得
        $data = $request->all();

        // セッションからユーザー情報を取得
        $user = session()->get('user');

        // Userの指定された明細IDのレコードを削除
        $user->meisais()->where('meisai_id', $data['searchID'])->delete();
    }

    /**
     * meisaisテーブルの情報をCSVに吐き出す
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function csv(Request $request){

        // リクエストの取得
        $data = $request->all();

        $fileName = '明細' . '.csv';

        $csvFileName = "/Users/junya_sato/Downloads/" . $fileName;

        $res = fopen($csvFileName, 'w');

        if ($res === FALSE) throw new Exception('ファイルの書き込みに失敗しました。');

        $csvHeader = array('明細ID', '残り回数', '残額', '引落日', '返済金額', '返済元金', '据置利息', '利息', '端数', '引落後残額');

        fputcsv($res, $csvHeader);

        // セッションからユーザー情報を取得
        $user = session()->get('user');

        $meisais = $user->meisais()->get();

        foreach ($meisais as $meisai) {
            fputcsv($res, [str_pad($meisai->meisai_id,4,0,STR_PAD_LEFT), $meisai->zankai . '回' , $meisai->zangaku, date('Y年n月j日', strtotime($meisai->hikibi . '+0 day')), $meisai->hensaigaku, $meisai->hensaimoto, $meisai->suerisoku, $meisai->risoku, $meisai->hasu, $meisai->atozangaku,]);
        }

        fclose($res);

        return Response::download($csvFileName, $fileName);
    }

    public function viewPreSet(Request $request){

        // リクエストの取得
        $data = $request->all();

        // セッションからユーザー情報を取得
        $user = session()->get('user');

        // 明細を取得
        $data['meisais'] = $meisais = $user->meisais()->orderBy('meisai_id', 'asc')->get();

        foreach ($meisais as $meisai){
            $years[date('Y', strtotime($meisai->hikibi . '+0 day'))] = date('Y', strtotime($meisai->hikibi . '+0 day'));
        }

        $data['msg'] = '奨学金の残額は '.$data['meisais'][0]->zangaku.'です。';

        return view('prepayset', $data);
    }

    public function redirectMenu(Request $request){
        $data = $request->all();
        var_dump($data['menuButton']);
//        return redirect('/login');
    }
}
