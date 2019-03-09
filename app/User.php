<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class User extends Model{

    // ORMを利用して自動的に挿入する項目は記載する。
    protected $guarded = [
        'name',
        'email',
        'password',
    ];

    /**
     * UserクラスとLogクラスは1対多の関係
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function logs(){
        return $this->hasmany('App\Log', 'email', 'email');
    }

    /**
     * UserクラスとMeisaiクラスは1対多の関係
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function meisais(){
        return $this->hasmany('App\Meisai', 'email', 'email');
    }

    /**
     * User１人につき、セッションは１つの関係
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function session(){
        return $this->hasOne('App\Session', 'id', 'id');
    }
}
