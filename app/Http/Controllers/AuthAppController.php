<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Log;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\AuthRequest;

class AuthAppController extends Controller{

    /**
     * ログイン画面へ遷移
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(){
        return view('login', [
            'msg' => "",
        ]);
    }

    /**
     * メールアドレスとパスワードを使用した認証
     *
     * @param AuthRequest $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function auth(AuthRequest $request){

        if(isset($request->name) && isset($request->email)){
            return view('index', [
                'email' => $request->email,
                'name' => $request->name,
            ]);
        }

        // 該当レコードを検索
        $user = User::where('email', $request->email)->first();

        // 該当レコードが存在
        if (isset($user)) {
            // ロックされているとき、入力画面を表示。
            if ($user->lock_status == 1){
                return view('login', ['msg' => $user->email . " はロックされています。"]);
            }

            // パスワードが一致しているとき、ログを無効化し、正常画面を表示
            if ($user->password == $request->password){

                // 正常ログの登録
                $user->logs()->save((New Log)->fill(['ip_address' => $request->ip(), 'status' => 0]));

                // ログの無効化
                $user->logs()->update(['status' => 0, 'updated_at' => date("Y/m/d H:i:s")]);

                return view('index', [
                    'email' => $user->email,
                    'name' => $user->name,
                    ]);
            }

            // エラーログの登録
            $user->logs()->save((New Log)->fill(['ip_address' => $request->ip(), 'status' => 1]));

            // 処理日に出力されているエラーログの個数を取得
            $log_count = $user->logs()->active()->activeDate()->count();

            // エラー件数が５件以上のとき、アカウントをロックし、エラー画面を表示
            if ($log_count >= 5){

                // アカウントのロック
                $user->update([
                    'lock_status' => 1,
                    'locked_at' => date("Y/m/d H:i:s"),
                ]);

                $msgs = [
                    "email 又は password を 5回 間違えたため",
                    "アカウントがロックされました。"
                ];

                return view('error', [
                    'msgs' => $msgs,
                ]);
            }

            return view('login', ['msg' => "※ email 又は password が違います。"]);
        }

        // エラーログの登録
        $user = new User;
        $user->email = $request->email;
        $user->logs()->save((new Log)->fill(['ip_address' => $request->ip(), 'status' => 1]));

        // ログイン画面を表示
        return view('login', ['msg' => "※ email 又は password が違います。"]);
    }
}
