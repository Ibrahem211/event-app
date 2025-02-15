<?php

namespace Database\Seeders;

use App\Models\Songer;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SongerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        Songer::create([
            'name'=>'songer',
            'parent_id'=>NULL,
            'price'=> 100,
            'image'=>'',
        ]);

        Songer::create([
            'name'=>'dj',
            'parent_id'=>NULL,
            'price'=> 100,
            'image'=>'',
        ]);

        Songer::create([
            'name'=>'stereo',
            'parent_id'=>NULL,
            'price'=> 100,
            'image'=>'',
        ]);

        Songer::create([
            'name'=>'amr dieab',
            'parent_id'=>1,
            'price'=> 100,
            'image'=>'',
        ]);
        Songer::create([
            'name'=>'nasef zaitoon',
            'parent_id'=>1,
            'price'=> 100,
            'image'=>'',
        ]);
        Songer::create([
            'name'=>'asala',
            'parent_id'=>1,
            'price'=> 100,
            'image'=>'',
        ]);
        Songer::create([
            'name'=>'bahaa alyuosef',
            'parent_id'=>1,
            'price'=> 100,
            'image'=>'',
        ]);

        Songer::create([
            'name'=>'ghith',
            'parent_id'=>2,
            'price'=> 100,
            'image'=>'',
        ]);

        Songer::create([
            'name'=>'broo',
            'parent_id'=>2,
            'price'=> 100,
            'image'=>'',
        ]);

        Songer::create([
            'name'=>'amjad',
            'parent_id'=>2,
            'price'=> 100,
            'image'=>'',
        ]);

        Songer::create([
            'name'=>'tamara',
            'parent_id'=>3,
            'price'=> 100,
            'image'=>'',
        ]);

        Songer::create([
            'name'=>'shahed',
            'parent_id'=>3,
            'price'=> 100,
            'image'=>'',
        ]);

        Songer::create([
            'name'=>'mohamed',
            'parent_id'=>3,
            'price'=> 100,
            'image'=>'',
        ]);
    }
}
