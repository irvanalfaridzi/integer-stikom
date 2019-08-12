<?php

use Illuminate\Database\Seeder;

class TeamsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('teams')->insert([
            'id'            => 'stikomdev',
            'name'          => 'Stikom Dev',
            'username'      => 'stikomdev',
            'password'      => bcrypt('1234567890'),
            'institution'   => 'Stikom Surabaya',
            'status'        => 'belum',
            'competition_id'=> 1,
        ]);
    }
}
