<?php

namespace Database\Seeders;

use App\Models\Place;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PlaceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        Place::create([
            'name'=>'hotel',
            'parent_id'=>Null,
            'adress_id'=>'1',
            'price'=> 100,
            'description' => 'i am king',
            'PhoneNumber' => '0999949999',
            'tele' => '@telee'
        ]);

        Place::create([
            'name'=>'resturant',
            'parent_id'=>Null,
            'adress_id'=>1,
            'price'=> 100,
            'description' => 'i am king',
            'PhoneNumber' => '0999699999',
            'tele' => '@tele0'
        ]);

        Place::create([
            'name'=>'four seasons',
            'parent_id'=>1,
            'adress_id'=>5,
            'price'=> 100,
            'description' => 'i am king',
            'PhoneNumber' => '0999899999',
            'tele' => '@tele99'
        ]);
        Place::create([
            'name'=>'alaa aldeen',
            'parent_id'=>2,
            'adress_id'=>6,
            'price'=> 100,
            'description' => 'i am king',
            'PhoneNumber' => '0999999991',
            'tele' => '@tele1'
        ]);
        Place::create([
            'name'=>'alasala',
            'parent_id'=>2,
            'adress_id'=>7,
            'price'=> 100,
            'description' => 'i am king',
            'PhoneNumber' => '0999999992',
            'tele' => '@tele2'
        ]);
        Place::create([
            'name'=>'makolat alsham',
            'parent_id'=>2,
            'adress_id'=>8,
            'price'=> 100,
            'description' => 'i am king',
            'PhoneNumber' => '0999999993',
            'tele' => '@tele3'
        ]);
        Place::create([
            'name'=>'ala alnar',
            'parent_id'=>1,
            'adress_id'=>9,
            'price'=> 100,
            'description' => 'i am king',
            'PhoneNumber' => '0999999994',
            'tele' => '@tele4'
        ]);
        Place::create([
            'name'=>'dama rose',
            'parent_id'=>1,
            'adress_id'=>10,
            'price'=> 100,
            'description' => 'i am king',
            'PhoneNumber' => '0999999995',
            'tele' => '@tele5'
        ]);

    }
}
