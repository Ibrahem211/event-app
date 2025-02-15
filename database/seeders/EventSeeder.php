<?php

namespace Database\Seeders;

use App\Models\Event;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Event::create([
            'categories'=>'maridges',
            'image'=> ''
        ]);
        Event::create([
            'categories'=>'parties',
            'image'=> ''
        ]);
        Event::create([
            'categories'=>'families',
            'image'=> ''
        ]);
        Event::create([
            'categories'=>'birthday party',
            'image'=> ''
        ]);
        Event::create([
            'categories'=>'graduation',
            'image'=> ''
        ]);
        Event::create([
            'categories'=>'concert',
            'image'=> ''
        ]);

    }
}
