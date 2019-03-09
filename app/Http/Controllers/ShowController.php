<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class ShowController extends Controller
{
    /**
     * 検索条件に該当するデータを抽出する
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request){

        // リクエストのセッション情報からユーザー情報を取得
        $sesdata = $request->session()->get('user');

        // セッションデータが存在しないときはログイン画面を表示
        if (!isset($sesdata)){
            return redirect('login');
        }

        // emailからUserを取得する。
        $user = User::where('email', $sesdata->email)->first();

        // Queryの基本形を作成
        $query = $user->meisais();

        $maxID = $user->meisais()->max('meisai_id');
        $minID = $user->meisais()->min('meisai_id');
        $minDate = date('1900-01-01 00:00:00');
        $maxDate = date('2100-01-31 00:00:00');
        $minZankai = 0;
        $maxZankai = 240;

        // 明細IDによる検索条件を追加
        if(isset($request->searchID)){
            $minID = (int)$request->searchID;
            $maxID = (int)$request->searchID;
        }

        // 明細IDによる検索条件を追加
        if(isset($request->searchID2)){
            $maxID = (int)$request->searchID2;
        }

        // 引落年月による検索条件を追加
        if (isset($request->year)){
            $minDate = date('Y-m-d H:i:s', strtotime($request->year . "-" . $request->month . "-" . "1" . '+0 month'));
            $maxDate = date('Y-m-d H:i:s', strtotime($request->year . "-" . $request->month . "-" . "31" . '+0 month'));
        }

        // 引落年月による検索条件を追加
        if (isset($request->year2)){
            $maxDate = date('Y-m-d H:i:s', strtotime($request->year2 . "-" . $request->month2 . "-" . "31" . '+0 month'));
        }

        // 残り回数による検索条件を追加
        if (isset($request->zankai)){
            $minZankai = $request->zankai;
            $maxZankai = $request->zankai;
        }

        // 残り回数による検索条件を追加
        if (isset($request->zankai2)){
            $maxZankai = $request->zankai2;
        }

        $meisais = $query
            ->moreThanID($minID)
            ->lessThanID($maxID)
            ->moreThanHikibi($minDate)
            ->lessThanHikibi($maxDate)
            ->moreThanZankai($minZankai)
            ->LessThanZankai($maxZankai)
            ->paginate(15);

        return view('show', [
            'items' => $meisais,
            'fitem' => $meisais->firstItem(),
            'litem' => $meisais->lastItem(),
            'mitem' => $user->meisais()->count(),
            'msg' => '',
        ]);
    }}
