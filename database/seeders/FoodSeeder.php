<?php

namespace Database\Seeders;

use App\Models\Food;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FoodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Food::create([
            'categories'=>'main meal',
            'parent_id'=>NULL,
            'price'=> 200,
            'image'=>'',
        ]);
        Food::create([
            'categories'=>'snack',
            'parent_id'=>NULL,
            'price'=> 200,
            'image'=>'',
        ]);
        Food::create([
            'categories'=>'dessert',
            'parent_id'=>NULL,
            'price'=> 200,
            'image'=>'',
        ]);
        Food::create([
            'categories'=>'drinks',
            'parent_id'=>NULL,
            'price'=> 200,
            'image'=>'',
        ]);
        Food::create([
            'categories'=>'kresby',
            'parent_id'=>1,
            'price'=> 150,
            'description' => 'Crispy chicken fingers fried in oil with salad and a little mayonnaise sauce on the side
            Maybe it will be the best choice',
            'image'=>'',
        ]);
        Food::create([
            'categories'=>'zenjer',
            'parent_id'=>1,
            'price'=> 200,
            'description' => 'i am king',
            'image'=>'',
        ]);

        Food::create([
            'categories'=>'bizza',
            'parent_id'=>1,
            'price'=> 200,
            'description' => 'i am king',
            'image'=>'',
        ]);

        Food::create([
            'categories'=>'sezer salad',
            'parent_id'=>2,
            'price'=> 200,
            'description' => 'i am king',
            'image'=>'',
        ]);
        Food::create([
            'categories'=>'soup',
            'parent_id'=>2,
            'price'=> 200,
            'description' => 'i am king',
            'image'=>'',
        ]);
        Food::create([
            'categories'=>'kreep',
            'parent_id'=>3,
            'price'=> 200,
            'description' => 'i am king',
            'image'=>'',
        ]);
        Food::create([
            'categories'=>'icecream',
            'parent_id'=>3,
            'price'=> 200,
            'description' => 'i am king',
            'image'=>'',
        ]);
        Food::create([
            'categories'=>'orange fresh',
            'parent_id'=>4,
            'price'=> 200,
            'description' => 'i am king',
            'image'=>'',
        ]);
        Food::create([
            'categories'=>'tea',
            'parent_id'=>4,
            'price'=> 200,
            'description' => 'i am king',
            'image'=>'',
        ]);
        Food::create([
            'categories'=>'beer',
            'parent_id'=>4,
            'price'=> 200,
            'description' => 'i am king',
            'image'=>'',
        ]);

        Food::create([
            'categories'=>'jjjj',
            'parent_id'=>NULL,
            'price'=> 200,
            'description' => 'i am king',
            'image'=>'',
        ]);

        Food::create([
            'categories'=>'jkjk',
            'parent_id'=>14,
            'price'=> 200,
            'description' => 'i am king',
            'image'=>'',
        ]);

    }
}
