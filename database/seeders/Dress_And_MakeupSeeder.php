<?php

namespace Database\Seeders;

use App\Models\Dress_And_Makeup;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class Dress_And_MakeupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        Dress_And_Makeup::create([
            'type'=>'dresses',
            'parent_id'=>NULL,
            'price'=> 200,
            'image'=>'',
        ]);
        Dress_And_Makeup::create([
            'type'=>'suites',
            'parent_id'=>NULL,
            'price'=> 200,
            'image'=>'',
        ]);
        Dress_And_Makeup::create([
            'type'=>'makeup',
            'parent_id'=>NULL,
            'price'=> 200,
            'image'=>'',
        ]);
        Dress_And_Makeup::create([
            'type'=>'maridge dress',
            'parent_id'=>1,
            'price'=> 200,
            'description' => 'i am king',
            'image'=>'',
        ]);
        Dress_And_Makeup::create([
            'type'=>'cravat',
            'parent_id'=>2,
            'price'=> 200,
            'description' => 'i am king',
            'image'=>'',
        ]);
        Dress_And_Makeup::create([
            'type'=>'semple makeup',
            'parent_id'=>3,
            'price'=> 200,
            'description' => 'i am king',
            'image'=>'',
        ]);
    }
}
