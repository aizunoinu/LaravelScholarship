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

        // リクエストのセッション情報からユーザー情報を取得する。
        $sesdata = $request->session()->get('user');

        // セッション情報がない場合はログイン画面を表示する。
        if(!isset($sesdata)){
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

        // リクエストのセッション情報からユーザーを取得する。
        $sesdata = $request->session()->get('user');

        // セッション情報が存在しないときは、ログイン画面を表示する
        if(!isset($sesdata)){
            return redirect('login');
        }

        // emailからUserを取得する。
        $user = User::where('email', $sesdata->email)->first();

        // 明細のIDが指定されているとき
//        if (isset($request->searchID)) {
//            $maxID = $request->searchID;
//            $minID = $request->searchID;
//        } else {
//            $maxID = $user->meisais()->max('meisai_id');
//            $minID = $user->meisais()->min('meisai_id');
//        }

        // ユーザーの明細を取得
        $meisais = $user->meisais()->paginate(15);

        //        if(isset($maxID) && isset($minID)){
//            $meisais = $user->meisais()->moreThanID($minID)->lessThanID($maxID)->paginate(15);
//            $fitem = $meisais->firstItem();
//            $litem = $meisais->lastItem();
//            $mitem = $user->meisais()->count();
//        }
//        else{
//            $meisais = $user->meisais()->get();
//            $fitem = '';
//            $litem = '';
//            $mitem = $user->meisais()->count();
//        }
        return view('show', [
            'items' => $meisais,
            'fitem' => $meisais->firstItem(),
            'litem' => $meisais->lastItem(),
            'mitem' => $user->meisais()->count(),
            'msg' => '',
        ]);
    }
}
