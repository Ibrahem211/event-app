<?php

namespace Database\Seeders;

use App\Models\User_event;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class User_eventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User_event::create([
            'user_id'=>2,
            'event_id'=>5,
            'place_id'=>1,
            'decoration_id'=>1,
            'food_id'=>11,
            'drees_and_makeup_id'=>5,
            'songer_id' => 1,
            'car_id' => 7,
            'status' => '1',
            'viewability' => '1',
            'completed' => '1',
            'date' => '2042-07-07',


        ]);
        User_event::create([
            'user_id'=>4,
            'event_id'=>4,
            'place_id'=>1,
            'decoration_id'=>2,
            'food_id'=>11,
            'drees_and_makeup_id'=>6,
            'songer_id' => 2,
            'car_id' => 2,
            'status' => '0',
            'date' => '2042-06-06',
        ]);
    }
}
