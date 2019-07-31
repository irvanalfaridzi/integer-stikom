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
            'password'      => bcrypt('password'),
            'institution'   => 'Stikom Surabaya',
            'status'        => 1,
            'competition_id'=> 1,
        ]);
    }
}
