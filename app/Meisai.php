<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Meisai extends Model
{
    /**
     * Eloquent ORMを使用して、自動的に設定される項目を設定禁止にする。
     *
     * @var array
     */
    protected $guarded = [
        'email'
    ];

    /**
     * MeisaiクラスとUserクラスは多対1の関係
     *
     * @return Illuminate\Database\Eloquent\Model
     */
    public function user()
    {
        return $this->belongsTo('App\User', 'email', 'email');
    }

    public function scopeGreatThanCreatedAt($query)
    {
        return $query->where('hikibi', '>=', date('Y年M月d日 0:0:0', time()));
    }

    public function scopeEqualsID($query, $meisai_id){
        return $query->where('meisai_id', '=', $meisai_id);
    }

    public function scopeLessThan($query, $meisai_id){
        return $query->where('meisai_id', '<=', $meisai_id);
    }

    public function scopeMoreThan($query, $meisai_id){
        return $query->where('meisai_id', '>=', $meisai_id);
    }

}
