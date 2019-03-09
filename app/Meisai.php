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
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\User', 'email', 'email');
    }

    /**
     * @param $query
     * @return mixed
     */
    public function scopeGreatThanCreatedAt($query)
    {
        return $query->where('hikibi', '>=', date('Y年M月d日 0:0:0', time()));
    }

    /**
     * @param $query
     * @param $meisai_id
     * @return mixed
     */
    public function scopeEqualsID($query, $meisai_id){
        return $query->where('meisai_id', '=', $meisai_id);
    }

    /**
     * @param $query
     * @param $meisai_id
     * @return mixed
     */
    public function scopeLessThanID($query, $meisai_id){
        return $query->where('meisai_id', '<=', $meisai_id);
    }

    /**
     * @param $query
     * @param $meisai_id
     * @return mixed
     */
    public function scopeMoreThanID($query, $meisai_id){
        return $query->where('meisai_id', '>=', $meisai_id);
    }

    /**
     * @param $query
     * @param $hikibi
     * @return mixed
     */
    public function scopeMoreThanHikibi($query, $hikibi){
        return $query->where('hikibi', '>=', $hikibi);
    }

    /**
     * @param $query
     * @param $hikibi
     * @return mixed
     */
    public function scopeLessThanHikibi($query, $hikibi){
        return $query->where('hikibi', '<=', $hikibi);
    }

    /**
     * @param $query
     * @param $hikibi
     * @return mixed
     */
    public function scopeMoreThanZankai($query, $zankai){
        return $query->where('zankai', '>=', $zankai);
    }

    /**
     * @param $query
     * @param $hikibi
     * @return mixed
     */
    public function scopeLessThanZankai($query, $zankai){
        return $query->where('zankai', '<=', $zankai);
    }
}
