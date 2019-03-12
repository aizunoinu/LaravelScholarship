<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class IndexController extends Controller
{
    /**
     * setting画面を表示
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function viewSet(Request $request){
        // セッションがない場合はログイン画面を表示する。
        if(!$request->session()->has('user')){
            return redirect('login');
        }

        // 設定画面を表示する。
        return view('setting');
    }

    /**
     * ユーザーの明細をすべて表示
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function viewShow(Request $request){

        // セッション情報が存在しないときは、ログイン画面を表示する
        if(!$request->session()->has('user')){
            return redirect('login');
        }

        // リクエストのセッション情報からユーザーを取得する。
        $sesdata = $request->session()->get('user');

        // emailからUserを取得する。
        $user = User::where('email', $sesdata->email)->first();

        // ユーザーの明細を取得
        $meisais = $user->meisais()->paginate(15);

        return view('show', [
            'items' => $meisais,
            'fitem' => $meisais->firstItem(),
            'litem' => $meisais->lastItem(),
            'mitem' => $user->meisais()->count(),
            'msg' => '',
        ]);
    }
}
