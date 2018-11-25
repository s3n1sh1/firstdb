<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        DB::table('tbuser')->insert([
            'tuuserid' => 1,
            'tuuser' => 'admin',
            'tupass' => bcrypt('adminn'),
            'tuname' => 'Admin',
            'turemk' => '',
            'turgid' => 'admin',
            'turgdt' => Date("Y-m-d H:i:s"),
            'tuchid' => 'admin',
            'tuchdt' => Date("Y-m-d H:i:s"),
            'tuchno' => 0,
            'tudlfg' => '0',
            'tudpfg' => '1',
            'tusrce' => 'Web',
            'tunote' => ''
        ]);
    }
}
