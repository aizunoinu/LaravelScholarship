<?php

use Illuminate\Database\Seeder;

class LogsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(){

        $time = date("Y/m/d H:i:s");

        $param = [
            'email' => 'sample@sample.com',
            'ip_address' => '192.168.11.1',
            'status' => '0',
            'created_at' => $time,
        ];

        DB::table('logs')
            ->insert($param);

        $param = [
            'email' => 'sato@metaps-payment.com',
            'ip_address' => '192.168.11.1',
            'status' => '0',
            'created_at' => $time,
        ];

        DB::table('logs')
            ->insert($param);

        $param = [
            'email' => 'mori@motonari.com',
            'ip_address' => '192.168.11.1',
            'status' => '0',
            'created_at' => $time,
        ];

        DB::table('logs')
            ->insert($param);

        $param = [
            'email' => 'mori@motonari.com',
            'ip_address' => '192.168.11.1',
            'status' => '0',
            'created_at' => $time,
        ];

        DB::table('logs')
            ->insert($param);

        $param = [
            'email' => 'mori@motonari.com',
            'ip_address' => '192.168.11.1',
            'status' => '0',
            'created_at' => $time,
        ];

        DB::table('logs')
            ->insert($param);
    }
}
