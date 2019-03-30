<?php

namespace App\Http\Controllers;

use App\User;
use App\Scholarship;
use Illuminate\http\Request;

class Setting extends Controller
{
    /**
     * 新規シミュレーションを行う
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Exception
     */
    public function __invoke(Request $request){

        // セッションが存在しないときは、ログイン画面を表示する。
        if(!session()->has('user')){
            return redirect('login');
        }

        // セッション情報を取得する。
        $session_data = session()->get('user');

        // リクエスト情報の取得
        $data = $request->all();

        // emailからUserを取得する。
        $user = User::where('email', $session_data['email'])->first();

        // 過去のシミュレーション結果を削除
        $user->meisais()->delete();

        // シミュレーションを実施
        $scholarship = new Scholarship($session_data['email'], $data['goukei'], $data['nenri'], $data['finyear'], $data['finmonth']);
        $scholarship->calcurateItems();
        $scholarship->hensaiSimulation();

        return redirect('login/show');
    }
}
