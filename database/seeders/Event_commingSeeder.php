<?php

namespace Database\Seeders;

use App\Models\Event_comming;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class Event_commingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        Event_comming::create([
            'name'=>'',
            'type'=>'families',
            'price'=>550,
            'location'=>'damascus',
            'description' => '',
            'Number_of_attendees'=>200,
            'date'=>'2024-05-15',
        ]);
        Event_comming::create([
            'name'=>'',
            'type'=>'concert',
            'price'=>200,
            'location'=>'latakia',
            'description' => '',
            'Number_of_attendees'=>100,
            'date'=>'2024-04-09',

        ]);
        Event_comming::create([
            'name'=>'',
            'type'=>'parties',
            'price'=>370,
            'location'=>'maydan',
            'description' => '',
            'Number_of_attendees'=>340,
            'date'=>'2024-04-09',

        ]);
        Event_comming::create([
            'name'=>'',
            'type'=>'parties',
            'price'=>400,
            'location'=>'homs',
            'description' => '',
            'Number_of_attendees'=>150,
            'date'=>'2024-01-03',

        ]);
        Event_comming::create([
            'name'=>'',
            'type'=>'graduation',
            'price'=>160,
            'location'=>'alsalehia',
            'description' => '',
            'Number_of_attendees'=>600,
            'date'=>'2024-10-04',

        ]);
        Event_comming::create([
            'name'=>'',
            'type'=>'families',
            'price'=>490,
            'location'=>'swayda',
            'description' => '',
            'Number_of_attendees'=>350,
            'date'=>'2024-06-20',

        ]);
    }
}

