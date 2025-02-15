<?php

namespace Database\Seeders;

use App\Models\Image_comming;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class Image_commingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        Image_comming::create([
            'event_commings_id'=>1,
            'image'=>'',
        ]);
        Image_comming::create([
            'event_commings_id'=>2,
            'image'=>'',
        ]);
        Image_comming::create([
            'event_commings_id'=>3,
            'image'=>'',
        ]);
        Image_comming::create([
            'event_commings_id'=>4,
            'image'=>'',
        ]);
             Image_comming::create([
            'event_commings_id'=>5,
            'image'=>'',
        ]);
    }
}
