<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use mysql_xdevapi\Exception;
use App\User;
use App\library\Scholarship;

class ScholarshipController extends Controller{

    /**
     * ユーザーの明細をすべて表示
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function viewShow(Request $request){

        // emailからUserを取得する。
        $user = User::where('email', $request->email)->first();

        // 明細のIDが指定されているとき
        if (isset($request->searchID)) {
            $maxID = $request->searchID;
            $minID = $request->searchID;
        } else {
            $maxID = $user->meisais()->max('meisai_id');
            $minID = $user->meisais()->min('meisai_id');
        }

        if(isset($maxID) && isset($minID)){
            $meisais = $user->meisais()->moreThanID($minID)->lessThanID($maxID)->paginate(15);
            $fitem = $meisais->firstItem();
            $litem = $meisais->lastItem();
            $mitem = $user->meisais()->count();
        }
        else{
            $meisais = $user->meisais()->get();
            $fitem = '';
            $litem = '';
            $mitem = $user->meisais()->count();
        }
        return view('show', [
            'email' => $request->email,
            'name' => $request->name,
            'items' => $meisais,
            'fitem' => $fitem,
            'litem' => $litem,
            'mitem' => $mitem,
            'msg' => '',
        ]);
    }

    /**
     * 新規シミュレーションを行う
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Exception
     */
    public function create(Request $request){
        // emailからUserを取得する。
        $user = User::where('email', $request->email)->first();

        // 過去のシミュレーション結果を削除
        $user->meisais()->delete();

        // シミュレーションを実施
        $scholarship = new Scholarship($request->email, $request->goukei, $request->nenri, $request->finyear, $request->finmonth);
        $scholarship->calcurateItems();
        $scholarship->hensaiSimulation();

        return redirect()->action('ScholarshipController@viewShow', ['name' => $request->name, 'email' => $request->email]);
    }

    /**
     * setting画面を表示
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function viewSet(Request $request){
        return view('setting', [
            'email' => $request->email,
            'name' => $request->name,
        ]);
    }

    /**
     * 削除ボタンが押下されたときに明細を削除する。
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
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

    /**
     * 検索条件に該当するデータを抽出する
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function search(Request $request){

        // emailからUserを取得する。
        $user = User::where('email', $request->email)->first();

        // Queryの基本形を作成
        $query = $user->meisais();

        $maxID = $user->meisais()->max('meisai_id');
        $minID = $user->meisais()->min('meisai_id');
        $minDate = date('1900-01-01 00:00:00');
        $maxDate = date('2100-01-31 00:00:00');
        $minZankai = 0;
        $maxZankai = 240;

        // 明細IDによる検索条件を追加
        if(isset($request->searchID)){
            $minID = (int)$request->searchID;
            $maxID = (int)$request->searchID;
        }

        // 明細IDによる検索条件を追加
        if(isset($request->searchID2)){
            $maxID = (int)$request->searchID2;
        }

        // 引落年月による検索条件を追加
        if (isset($request->year)){
            $minDate = date('Y-m-d H:i:s', strtotime($request->year . "-" . $request->month . "-" . "1" . '+0 month'));
            $maxDate = date('Y-m-d H:i:s', strtotime($request->year . "-" . $request->month . "-" . "31" . '+0 month'));
        }

        // 引落年月による検索条件を追加
        if (isset($request->year2)){
            $maxDate = date('Y-m-d H:i:s', strtotime($request->year2 . "-" . $request->month2 . "-" . "31" . '+0 month'));
        }

        // 残り回数による検索条件を追加
        if (isset($request->zankai)){
            $minZankai = $request->zankai;
            $maxZankai = $request->zankai;
        }

        // 残り回数による検索条件を追加
        if (isset($request->zankai2)){
            $maxZankai = $request->zankai2;
        }

        $meisais = $query
            ->moreThanID($minID)
            ->lessThanID($maxID)
            ->moreThanHikibi($minDate)
            ->lessThanHikibi($maxDate)
            ->moreThanZankai($minZankai)
            ->LessThanZankai($maxZankai)
            ->paginate(15);

        $fitem = $meisais->firstItem();
        $litem = $meisais->lastItem();
        $mitem = $user->meisais()->count();

        return view('detail', [
            'email' => $request->email,
            'name' => $request->name,
            'items' => $meisais,
            'msg' => '',
            'title' => $request->title,
            'searchID' => $minID,
            'searchID2' => $maxID,
            'year' => date('Y', strtotime( $minDate . '+0 month')),
            'month' => date('n', strtotime( $minDate . '+0 month')),
            'year2' => date('Y', strtotime( $maxDate . '+0 month')),
            'month2' => date('n', strtotime( $maxDate . '+0 month')),
            'zankai' => $minZankai,
            'zankai2' => $maxZankai,
            'fitem' => $meisais->firstItem(),
            'litem' => $meisais->lastItem(),
            'fitem' => $fitem,
            'litem' => $litem,
            'mitem' => $mitem,
        ]);
    }

    /**
     * メニューに戻るボタンの処理
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function viewMenu(Request $request){
        return view('index', [
            'name' => $request->name,
            'email' => $request->email,
        ]);
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

    public function ajaxPrePay(Request $request){

        $user = User::where('email', $request->email)->first();

//        \Log::info($user->name);
//        \Log::info($user->email);

        echo '<table align="center" border="1">';
        echo '<tr><th>名前</th><th>メールアドレス</th></tr>';
        echo '<tr><td>' . $user->name . '</td><td>' . $user->email . '</td></tr>';
        echo '</table>';
    }
}
 
