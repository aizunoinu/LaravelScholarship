<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class Index extends Controller
{
    /**
     * setting画面を表示
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function viewSet(){

        // セッションがない場合はログイン画面を表示する。
        if(!session()->has('user')){
            return redirect('login');
        }

        // 設定画面を表示する。
        return view('setting');
    }

    /**
     * ユーザーの明細をすべて表示
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function viewShow(){

        // セッション情報が存在しないときは、ログイン画面を表示する
        if(!session()->has('user')){
            return redirect('login');
        }

        // リクエストのセッション情報からユーザーを取得する。
        $session_data = session()->get('user');

        // emailからUserを取得する。
        $user = User::where('email', $session_data['email'])->first();

        // ユーザーの明細を取得
        $data['meisais'] = $meisais = $user->meisais()->paginate(15);
        $data['first_item_num'] = $meisais->firstItem();
        $data['last_item_num'] = $meisais->lastItem();
        $data['total_item_num'] = $user->meisais()->count();

        return view('show', $data);
    }
}
