<?php

namespace App\Http\Controllers;

use App\Session;
use Illuminate\Http\Request;
use App\User;
use App\Log;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\AuthRequest;

class AuthAppController extends Controller
{

    /**
     * ビューの表示
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        // リクエストに格納されているセッション情報を取得
        $sesdata = $request->session()->get('user');

        // セッションデータが存在しない時
        if (!isset($sesdata)) {
            return view('login', [
                'msg' => "",
            ]);
        }

        // セッションデータが存在する時
        return view('index', [
            'name' => $sesdata->name,
            'email' => $sesdata->email,
        ]);

    }

    /**
     * メールアドレスとパスワードを使用した認証
     *
     * @param AuthRequest $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function auth(AuthRequest $request)
    {

        // 該当レコードを検索
        $user = User::where('email', $request->email)->first();

        // 該当レコードが存在
        if (isset($user)) {
            // ロックされているとき、入力画面を表示。
            if ($user->lock_status == 1) {
                return view('login', ['msg' => $user->email . " はロックされています。"]);
            }

            // パスワードが一致しているとき、ログを無効化し、正常画面を表示
            if ($user->password == $request->password) {

                // 正常ログの登録
                $user->logs()->save((New Log)->fill(['ip_address' => $request->ip(), 'status' => 0]));

                // ログの無効化
                $user->logs()->update(['status' => 0, 'updated_at' => date("Y/m/d H:i:s")]);

                // リクエストにセッション情報を保存する。
                $request->session()->put('user', $user);

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

            return view('login', ['msg' => "※ email 又は password が違います。"]);
        }

        // エラーログの登録
        $user = new User;
        $user->email = $request->email;
        $user->logs()->save((new Log)->fill(['ip_address' => $request->ip(), 'status' => 1]));

        // ログイン画面を表示
        return view('login', ['msg' => "※ email 又は password が違います。"]);
    }

    /**
     * リクエストからセッションを削除してログイン画面へ戻る。
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function logout(Request $request){
        $request->session()->forget('user');

        return redirect('/login');
    }
}
