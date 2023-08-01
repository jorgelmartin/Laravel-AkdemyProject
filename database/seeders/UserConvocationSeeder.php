<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserConvocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        DB::table('user_convocation')->insert([
            [
                'convocation_id' => '1',
                'user_id' => '2',
                'status' => '0'
            ],
            [
                'convocation_id' => '2',
                'user_id' => '2',
                'status' => '0'
            ],
            [
                'convocation_id' => '3',
                'user_id' => '2',
                'status' => '0'
            ],
        ]);
    }
}
