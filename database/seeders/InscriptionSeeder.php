<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InscriptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        DB::table('inscription')->insert([
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
