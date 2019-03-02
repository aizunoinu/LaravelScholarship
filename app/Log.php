<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Log extends Model{

    // ORMを利用して自動的に挿入する項目は記載する。
    protected $guarded = [
        'email',
    ];

    /**
     * 有効ログの取得
     *
     * @param $query
     */
    public function scopeActive($query){
        return $query->where('status', '=', 1);
    }

    /**
     * 処理日のログを取得
     *
     * @param $query
     */
    public function scopeActiveDate($query){
        return $query->where('created_at', '>=', date("Y/m/d 00:00:00"));
    }
}
