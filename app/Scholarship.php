<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use \Datetime;

class Scholarship extends Model
{
    /**
     * Scholarship constructor.
     *
     * @param $email
     * @param $goukei
     * @param $nenri
     * @param $finyear
     * @param $finmonth
     */
    public function __construct($email, $goukei, $nenri, $finyear, $finmonth){
        $this->email = $email;
        $this->hensaiSogaku = (int)$goukei;
        $this->nenri = (float)$nenri;
        $this->taiyoEndYear = $finyear;
        $this->taiyoEndmonth = $finmonth;
        $this->basedate = $finyear . '-' . $finmonth . '-27';
    }

    /**
     * ScholarshipクラスとMeisaiクラスとの関係は1対多
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function meisais(){
        return $this->hasmany('App\Meisai', 'email', 'email');
    }

    /**
     * 計算に必要な変数を算出
     *
     * @return void
     */
    public function calcurateItems(){
        // 月利の算出
        $this->getsuri = $this->nenri / 100 / 12;

        // 返済年数と返済回数を算出
        $this->hensaiNensu = $this->gesaiNensu();
        $this->hensaiKaisu = 12 * $this->hensaiNensu;

        // 据え置き利息を算出
        $wTotalSueokiRisoku = $this->getTotalSueokiRisoku();
        $this->tukiSueokiRisoku = (int)($wTotalSueokiRisoku / $this->hensaiKaisu);

        // 小数点付きの月据え置き利息から、小数点以下を切り捨てた月据え置き利息 * 返済回数を減算したものを据え置き利息の余りとする。
        $this->amariGoukeigaku = (int)($wTotalSueokiRisoku - ($this->tukiSueokiRisoku * $this->hensaiKaisu));

        // 奨学金の月返済額（据置利息以外）を求める
        $this->tukiHensaigaku = (int)$this->getTukiHensaigaku();

        // 月に返済する金額を求める
        $this->hensaigaku = $this->tukiHensaigaku + $this->tukiSueokiRisoku;

        // 毎月奨学金を返済していった場合最終的に支払う奨学金の総額を算出する。
        $this->nomalHensaiSogaku = $this->hensaigaku * $this->hensaiKaisu + $this->amariGoukeigaku;
    }

    /**
     * 借用金額から返済年数を算出
     *
     * @return float|int
     */
    public function gesaiNensu(){
        // 返済年数の取得(公式サイトより)
        if($this->hensaiSogaku <= 200000) return $this->hensaiSogaku / 30000;
        if($this->hensaiSogaku <= 400000) return $this->hensaiSogaku / 40000;
        if($this->hensaiSogaku <= 500000) return $this->hensaiSogaku / 50000;
        if($this->hensaiSogaku <= 600000) return $this->hensaiSogaku / 60000;
        if($this->hensaiSogaku <= 700000) return $this->hensaiSogaku / 70000;
        if($this->hensaiSogaku <= 900000) return $this->hensaiSogaku / 80000;
        if($this->hensaiSogaku <= 1100000) return $this->hensaiSogaku / 90000;
        if($this->hensaiSogaku <= 1300000) return $this->hensaiSogaku / 100000;
        if($this->hensaiSogaku <= 1500000) return $this->hensaiSogaku / 110000;
        if($this->hensaiSogaku <= 1700000) return $this->hensaiSogaku / 120000;
        if($this->hensaiSogaku <= 1900000) return $this->hensaiSogaku / 130000;
        if($this->hensaiSogaku <= 2100000) return $this->hensaiSogaku / 140000;
        if($this->hensaiSogaku <= 2300000) return $this->hensaiSogaku / 150000;
        if($this->hensaiSogaku <= 2500000) return $this->hensaiSogaku / 160000;
        if($this->hensaiSogaku <= 3400000) return $this->hensaiSogaku / 170000;
        if(3400001 <= $this->hensaiSogaku) return 20;
    }

    /**
     * 合計の据え置き利息を算出
     *
     * @return float|int
     */
    public function getTotalSueokiRisoku(){
        // 返済総額 * 年利（百分率） * 奨学金の貸与終了から返済開始までの日数180日 / 1年
        return $this->hensaiSogaku * ($this->nenri / 100) * 180 / 365;
    }

    /**
     * 月の返済額を算出
     *
     * @return float|int
     */
    public function getTukiHensaigaku(){
        // 返済総額 * 月利 * (1 + 月利) ^ 返済回数 / ((1 + 月利) ^ 返済回数 - 1)
        return $this->hensaiSogaku * $this->getsuri * pow((1 + $this->getsuri), $this->hensaiKaisu) / (pow((1 + $this->getsuri), $this->hensaiKaisu) - 1);
    }

    /**
     * シミュレーションを作成
     *
     * @throws \Exception
     */
    public function hensaiSimulation(){
        $hensaiCount = 0;
        // インスタンス変数からローカル変数に値をコピーする
        $hensaiKaisu = $this->hensaiKaisu;
        $hensaiSogaku = $this->hensaiSogaku;
        $amariGoukeigaku = $this->amariGoukeigaku;
        $hensaiDate = date('Y-m-d', strtotime($this->basedate . '+6 month'));

        while(1){
            // 月返済額の利息を計算する。
            $risoku = (int)($hensaiSogaku * $this->getsuri);

            // 27日が休日かどうか判定し、休日のときは翌月曜日を返済日として返す
            $whensaiDate = $this->getHensaiDate($hensaiDate);

            if($hensaiSogaku <= $this->hensaigaku) {
                $this->meisais()->save((new Meisai)->fill([
                    'meisai_id' => str_pad($hensaiCount + 1,4,0,STR_PAD_LEFT),
                    'zankai' => $hensaiKaisu - $hensaiCount . '回',
                    'zangaku' => number_format($hensaiSogaku) . '円',
                    'hikibi' => $whensaiDate,
                    'hensaigaku' => number_format($hensaiSogaku + $amariGoukeigaku) . '円',
                    'hensaimoto' => number_format($hensaiSogaku) . '円',
                    'suerisoku' => number_format($this->tukiSueokiRisoku) . '円',
                    'risoku' => number_format($risoku) . '円',
                    'hasu' => number_format($amariGoukeigaku - $this->tukiSueokiRisoku - $risoku) . '円',
                    'atozangaku' => 0 . '円',
                ]));
                $hensaiSogaku = 0;
                $hensaiCount = $hensaiCount + 1;
                $hensaiDate = date('Y-n-j', strtotime($hensaiDate . '+1 month'));
                break;
            }
            else {
                $this->meisais()->save((new Meisai)->fill([
                    'meisai_id' => $hensaiCount + 1,
                    'zankai' => $hensaiKaisu - $hensaiCount . '回',
                    'zangaku' => number_format($hensaiSogaku) . '円',
                    'hikibi' => $whensaiDate,
                    'hensaigaku' => number_format($this->hensaigaku) . '円',
                    'hensaimoto' => number_format($this->hensaigaku - $this->tukiSueokiRisoku - $risoku) . '円',
                    'suerisoku' => number_format($this->tukiSueokiRisoku) . '円',
                    'risoku' => number_format($risoku) . '円',
                    'hasu' => 0 . '円',
                    'atozangaku' => number_format($hensaiSogaku - ($this->hensaigaku - $this->tukiSueokiRisoku - $risoku)) . '円',
                ]));
                // 返済した金額分、返済総額を減額する。
                $hensaiSogaku = $hensaiSogaku - ($this->hensaigaku - $this->tukiSueokiRisoku - $risoku);
                $hensaiCount = $hensaiCount + 1;
                $hensaiDate = date('Y-n-j', strtotime($hensaiDate . '+1 month'));
            }
        }
   }

    /**
     * 返済予定日が土日の場合はよく月曜日に振替
     *
     * @param $hensaiDate
     * @return false|string
     * @throws \Exception
     */
   public function getHensaiDate($hensaiDate){
       $datetime = new DateTime($hensaiDate);

       if((int)$datetime->format('w') == 0) return date('Y年n月j日', strtotime($hensaiDate . '+1 day'));
       elseif((int)$datetime->format('w') == 6) return date('Y年n月j日', strtotime($hensaiDate . '+2 day'));
       else return date('Y年n月j日', strtotime($hensaiDate . '+0 day'));
    }
}
