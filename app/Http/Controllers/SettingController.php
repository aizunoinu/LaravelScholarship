<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Http\Libraries\Scholarship;

class SettingController extends Controller
{
    /**
     * 新規シミュレーションを行う
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Exception
     */
    public function create(Request $request){

        // リクエストからセッション情報を取得する。
        $sesdata = $request->session()->get('user');

        // セッションが存在しないときは、ログイン画面を表示する。
        if(!isset($sesdata)){
            return redirect('login');
        }

        // emailからUserを取得する。
        $user = User::where('email', $sesdata->email)->first();

        // 過去のシミュレーション結果を削除
        $user->meisais()->delete();

        // シミュレーションを実施
        $scholarship = new Scholarship($sesdata->email, $request->goukei, $request->nenri, $request->finyear, $request->finmonth);
        $scholarship->calcurateItems();
        $scholarship->hensaiSimulation();

        return redirect('login/show');
    }
}
