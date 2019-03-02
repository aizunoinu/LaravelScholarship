<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use mysql_xdevapi\Exception;
use PHPUnit\Runner\ResultCacheExtension;
use Illuminate\Database\Eloquent\Collection;
use App\User;
use App\Meisai;
use App\Scholarship;
use Illuminate\Support\Facades\View;
use Symfony\Component\VarDumper\VarDumper;
use App\Http\Requests\SettingRequest;
use App\Http\Requests\SearchRequest;

class ScholarshipController extends Controller{

    /**
     * ユーザーの明細をすべて表示
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request){

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
            $meisais = $user->meisais()->moreThan($minID)->lessThan($maxID)->paginate(15);
        }
        else{
            $meisais = $user->meisais()->get();
        }

//        var_dump($meisais);
        return view('show', [
            'email' => $request->email,
            'name' => $request->name,
            'items' => $meisais,
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
    public function create(SettingRequest $request){
        // emailからUserを取得する。
        $user = User::where('email', $request->email)->first();

        // 過去のシミュレーション結果を削除
        $user->meisais()->delete();

        // シミュレーションを実施
        $scholarship = new Scholarship($request->email, $request->goukei, $request->nenri, $request->finyear, $request->finmonth);
        $scholarship->calcurateItems();
        $scholarship->hensaiSimulation();

        return redirect()->action('ScholarshipController@index', ['name' => $request->name, 'email' => $request->email]);
    }

    /**
     * setting画面を表示
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function setting(Request $request){
        return view('setting', [
            'email' => $request->email,
            'name' => $request->name,
        ]);
    }

    public function detail(Request $request){
        // emailからUserを取得する。
        $user = User::where('email', $request->email)->first();

        // Userの指定された明細IDのレコードを取得
        $meisais = $user->meisais()->where('meisai_id', $request->searchID)->get();

        return view('detail', [
            'email' => $request->email,
            'name' => $request->name,
            'items' => $meisais,
        ]);
    }

    /**
     * 削除ボタンが押下されたときに明細を削除する。
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete(Request $request){
        // emailからUserを取得する。
        $user = User::where('email', $request->email)->first();

        // Userの指定された明細IDのレコードを削除
        $user->meisais()->where('meisai_id', $request->searchID)->delete();

        return redirect()->action('ScholarshipController@index', ['name' => $request->name, 'email' => $request->email]);
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

        $meisais = $user->meisais()->moreThan($minID)->lessThan($maxID)->get();

        foreach ($meisais as $meisai) {
            fputcsv($res, [$meisai->meisai_id, $meisai->zankai, $meisai->zangaku, $meisai->hikibi, $meisai->hensaigaku, $meisai->hensaimoto, $meisai->suerisoku, $meisai->risoku, $meisai->hasu, $meisai->atozangaku,]);
        }

        fclose($res);

        return Response::download($csvFileName, $fileName);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function search(Request $request){

//        var_dump($request->searchID);
        // emailからUserを取得する。
        $user = User::where('email', $request->email)->first();

        $query = $user->meisais();

        if(isset($request->searchID)){
            $maxID = (int)$request->searchID;
            $minID = (int)$request->searchID;
        }
        else {
            $maxID = $user->meisais()->max('meisai_id');
            $minID = $user->meisais()->min('meisai_id');
        }

        $query = $query->moreThan($minID)->lessThan($maxID);

        $meisais = $query->get();
//        var_dump($meisais[0]->meisai_id);
        return view('show', [
            'email' => $request->email,
            'name' => $request->name,
            'items' => $meisais,
            'msg' => '',
        ]);
    }
}
 
