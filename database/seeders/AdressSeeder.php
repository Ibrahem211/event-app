<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Adress;


class AdressSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Adress::create([
            'name'=>'Damascus',
            'parent_id'=> NULL,
         ]);
        Adress::create([
            'name'=>'Homs',
            'parent_id'=>NULL,
        ]);
        Adress::create([
            'name'=>'latakia',
            'parent_id'=>NULL,
        ]);
        Adress::create([
            'name'=>'Suwayda',
            'parent_id'=>Null,
        ]);
        Adress::create([
            'name'=>'maydan',
            'parent_id'=>1,
        ]);
        Adress::create([
            'name'=>'alsalehia',
            'parent_id'=>1,
        ]);
        Adress::create([
            'name'=>'albaramka',
            'parent_id'=>1,
        ]);
        Adress::create([
            'name'=>'maza',
            'parent_id'=>1,
        ]);

        Adress::create([
            'name'=>'Al Malki',
            'parent_id'=>1,
        ]);

        Adress::create([
            'name'=>'alrastan',
            'parent_id'=>2,
        ]);
        Adress::create([
            'name'=>'mharda',
            'parent_id'=>2,
        ]);
        Adress::create([
            'name'=>'marmareta',
            'parent_id'=>2,
        ]);

        Adress::create([
            'name'=>'Al Dablan',
            'parent_id'=>2,
        ]);

        Adress::create([
            'name'=>'hadarah',
            'parent_id'=>2,
        ]);

        Adress::create([
            'name'=>'alzeraa',
            'parent_id'=>3,
        ]);
        Adress::create([
            'name'=>'jablah',
            'parent_id'=>3,
        ]);
        Adress::create([
            'name'=>'solenfa',
            'parent_id'=>3,
        ]);

        Adress::create([
            'name'=>'blue beach',
            'parent_id'=>3,
        ]);

        Adress::create([
            'name'=>'al samra',
            'parent_id'=>3,
        ]);

        Adress::create([
            'name'=>'salkhad',
            'parent_id'=>4,
        ]);
        Adress::create([
            'name'=>'shahba',
            'parent_id'=>4,
        ]);
        Adress::create([
            'name'=>'alquraia',
            'parent_id'=>4,
        ]);

        Adress::create([
            'name'=>'Almajdal',
            'parent_id'=>4,
        ]);

        Adress::create([
            'name'=>'Kanawat',
            'parent_id'=>4,
        ]);

    }
}
