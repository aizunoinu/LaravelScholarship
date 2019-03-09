<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use mysql_xdevapi\Exception;
use App\User;

class ScholarshipController extends Controller{

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
 
