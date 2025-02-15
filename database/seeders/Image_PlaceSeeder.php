<?php

namespace Database\Seeders;

use App\Models\Image_Place;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class Image_PlaceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        Image_Place::create([
            'image'=>'',
            'place_id'=>1,
        ]);
        Image_Place::create([
            'image'=>'',
            'place_id'=>2,
        ]);
        Image_Place::create([
            'image'=>'',
            'place_id'=>3,
        ]);
        Image_Place::create([
            'image'=>'',
            'place_id'=>4,
        ]);
        Image_Place::create([
            'image'=>'',
            'place_id'=>5,
        ]);
        Image_Place::create([
            'image'=>'',
            'place_id'=>6,
        ]);
    }
}
