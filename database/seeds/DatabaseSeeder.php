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
            'tuuser' => 'sa',
            'tupass' => bcrypt('s3nd4bergurau'),
            'tuname' => 'sa',
            'turemk' => '',
            'turgid' => 'sa',
            'turgdt' => Date("Y-m-d H:i:s"),
            'tuchid' => 'sa',
            'tuchdt' => Date("Y-m-d H:i:s"),
            'tuchno' => 0,
            'tudlfg' => '0',
            'tudpfg' => '1',
            'tusrce' => 'Web',
            'tunote' => ''
        ]);

        DB::table('tbuser')->insert([
            'tuuserid' => 2,
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

        DB::table('tbuser')->insert([
            'tuuserid' => 3,
            'tuuser' => 'admin2',
            'tupass' => bcrypt('admin123'),
            'tuname' => 'Admin2',
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

        DB::table('tbuser')->insert([
            'tuuserid' => 4,
            'tuuser' => 'admin3',
            'tupass' => bcrypt('admin123'),
            'tuname' => 'Admin3',
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

        DB::table('tbuser')->insert([
            'tuuserid' => 5,
            'tuuser' => 'admin4',
            'tupass' => bcrypt('admin123'),
            'tuname' => 'Admin4',
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
