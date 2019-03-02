<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(){

        $param = [
          'name' => 'Sample',
          'email' => 'sample@sample.com',
          'password' => 'test',
          'lock_status' => '0',
        ];

        DB::table('users')
            ->insert($param);

        $param = [
            'name' => '毛利元就',
            'email' => 'mori@motonari.com',
            'password' => 'test',
            'lock_status' => '0',
        ];

        DB::table('users')
            ->insert($param);

        $param = [
            'name' => '明智光秀',
            'email' => 'akechi@mitsuhide.com',
            'password' => 'test',
            'lock_status' => '0',
        ];

        DB::table('users')
            ->insert($param);
    }
}
