<?php

namespace App\Http\Controllers;

use App\User;
use App\Log;
use Illuminate\Support\Facades\Input;

class Login extends Controller
{
    /**
     * ビューの表示
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {

        // セッションが存在すれば、indexビューを表示
        if(session()->has('user')){
            return view('index');
        }

        // ビューのリクエストを取得する。
        $data = Input::all();

        // リクエスト情報なし
        if (empty($data)){
            return view('login', [
                'msg' => "",
            ]);
        }

        // 該当レコードを検索
        $user = User::where('email', $data['email'])->first();

        // 該当レコードが存在
        if (isset($user)) {
            // ロックされているとき、入力画面を表示。
            if ($user->lock_status == 1) {
                return view('login', ['msg' => $user->email . " はロックされています。"]);
            }

            // パスワードが一致しているとき、ログを無効化し、正常画面を表示
            if ($user->password == $data['password']) {

                // 正常ログの登録
                $user->logs()->save((New Log)->fill(['ip_address' => $_SERVER["REMOTE_ADDR"], 'status' => 0]));

                // ログの無効化
                $user->logs()->update(['status' => 0, 'updated_at' => date("Y/m/d H:i:s")]);

                // リクエストにセッションを保存する。
                session()->put('user', $user);

                // index画面を表示する
                return view('index');
            }

            // エラーログの登録
            $user->logs()->save((New Log)->fill(['ip_address' => $_SERVER["REMOTE_ADDR"], 'status' => 1]));

            // 処理日に出力されているエラーログの個数を取得
            $log_count = $user->logs()->active()->activeDate()->count();

            // エラー件数が５件以上のとき、アカウントをロックし、エラー画面を表示
            if ($log_count >= 5) {

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

            // Login画面を表示
            return view('login', ['msg' => "※ email 又は password が違います。"]);
        }

        // エラーログの登録
        $user = new User;
        $user->email = $data['email'];
        $user->logs()->save((new Log)->fill(['ip_address' => $_SERVER["REMOTE_ADDR"], 'status' => 1]));

        // Login画面を表示
        return view('login', ['msg' => "※ email 又は password が違います。"]);
    }

    /**
     * セッションを削除して、/loginへリダイレクト
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function logout(){
        // リクエストのセッション情報を破棄
        session()->forget('user');

        // Login画面へリダイレクト
        return redirect('/login');
    }
}
