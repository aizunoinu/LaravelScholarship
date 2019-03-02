<?php

use Illuminate\Database\Seeder;

class MeisaisTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $param = [
            'email' => 's1200015junya@gmail.com',
            'zankai' => '2回',
            'zangaku' => '32,540円',
            'hikibi' => '2018年4月27日',
            'hensaigaku' => '16,270円',
            'hensaimoto' => '16,170円',
            'suerisoku' => '3円',
            'risoku' => '97円',
            'hasu' => '0円',
            'atozangaku' => '16,270円',
            'created_at' => date("Y/m/d H:i:s"),
            'updated_at' => date("Y/m/d H:i:s"),
        ];

        DB::table('meisais')
            ->insert($param);

        $param = [
            'email' => 's1200015junya@gmail.com',
            'zankai' => '1回',
            'zangaku' => '16,270円',
            'hikibi' => '2018年5月28日',
            'hensaigaku' => '16,270円',
            'hensaimoto' => '16,170円',
            'suerisoku' => '3円',
            'risoku' => '97円',
            'hasu' => '0円',
            'atozangaku' => '0円',
            'created_at' => date("Y/m/d H:i:s"),
            'updated_at' => date("Y/m/d H:i:s"),
        ];

        DB::table('meisais')
            ->insert($param);

    }
}
