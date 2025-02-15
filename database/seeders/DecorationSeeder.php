<?php

namespace Database\Seeders;

use App\Models\Decoration;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DecorationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        Decoration::create([
            'type'=>'tabel',
            'parent_id'=>NULL,
            'price'=> 200,
            'description' => 'i am king',
            'image'=>'',
        ]);
        Decoration::create([
            'type'=>'Innovative Lighting',
            'parent_id'=>NULL,
            'price'=> 200,
            'description' => 'i am king',
            'image'=>'',
        ]);
        Decoration::create([
            'type'=>'firework',
            'parent_id'=>NULL,
            'price'=> 200,
            'description' => 'i am king',
            'image'=>'',
        ]);

        Decoration::create([
            'type'=>'Flower',
            'parent_id'=>NULL,
            'price'=> 200,
            'description' => 'i am king',
            'image'=>'',
        ]);

        Decoration::create([
            'type'=>'Square wooden table',
            'parent_id'=>1,
            'price'=> 350,
            'description' => 'A dark brown square wooden table for four people, decorated with handcrafts',
            'image'=>'',
        ]);
        Decoration::create([
            'type'=>'Round wooden table',
            'parent_id'=>1,
            'price'=> 450,
            'description' => 'A light brown round wooden table decorated with seashells that seats six people',
            'image'=>'',
        ]);

        Decoration::create([
            'type'=>'Elegant rustic style',
            'parent_id'=>1,
            'price'=> 500,
            'description' => 'A rectangular table for eight people, decorated with a classic white cover and chairs made of oak wood.',
            'image'=>'',
        ]);

        Decoration::create([
            'type'=>'Crystal table',
            'parent_id'=>1,
            'price'=> 550,
            'description' => 'Made of high-quality crystal glass, it can accommodate eight or ten people, decorated with a transparent white silk cover and accompanied by seats made of pure glass.',
            'image'=>'',
        ]);

        Decoration::create([
            'type'=>'candle',
            'parent_id'=>2,
            'price'=> 5,
            'description' => 'Candles with a beautiful scent and different colors
            There are many shapes and colors',
            'image'=>'',
        ]);
        Decoration::create([
            'type'=>'spotlights',
            'parent_id'=>2,
            'price'=> 7,
            'description' => 'Types of electric lamps that are used to illuminate large areas or outdoor areas. It provides powerful,
             wide-range illumination to improve visibility and safety in the shade',
            'image'=>'',
        ]);
        Decoration::create([
            'type'=>'Lanterns',
            'parent_id'=>2,
            'price'=> 4,
            'description' => 'A nice decoration with a classic nature that relies on hanging a lantern above each table of the event,
             perhaps it will be the best choice for you',
            'image'=>'',
        ]);
        Decoration::create([
            'type'=>'Lighting bar',
            'parent_id'=>2,
            'price'=> 12,
            'description' => 'A ribbon 5 to 7 meters long that contains many colors that can give your event a beautiful and bright character.',
            'image'=>'',
        ]);

        Decoration::create([
            'type'=>'Light bomb',
            'parent_id'=>3,
            'price'=> 3,
            'description' => 'A harmless light-powder bomb that emits several colors at once.',
            'image'=>'',
        ]);

        Decoration::create([
            'type'=>'Crackers',
            'parent_id'=>3,
            'price'=> 2,
            'description' => 'Sticks of explosive dynamite make many sounds
            It is somewhat dangerous, so care must be taken when using it.',
            'image'=>'',
        ]);

        Decoration::create([
            'type'=>'Smoke bomb',
            'parent_id'=>3,
            'price'=> 5,
            'description' => 'A bomb that is not dangerous at all does not contain gunpowder
            When lit, a cloud of smoke emerges in different shapes and colors
            Be careful not to use it in closed places.',
            'image'=>'',
        ]);

        Decoration::create([
            'type'=>'White roses arch',
            'parent_id'=>4,
            'price'=> 230,
            'description' => 'A solid metal arch decorated with the most beautiful white roses in a beautiful spiral shape.',
            'image'=>'',
        ]);

        Decoration::create([
            'type'=>'Red roses',
            'parent_id'=>4,
            'price'=> 11,
            'description' => 'The well-known rose has a distinctive scent and beautiful colour
            Bright red.',
            'image'=>'',
        ]);

        Decoration::create([
            'type'=>'Sunflower rose',
            'parent_id'=>4,
            'price'=> 15,
            'description' => 'The sunflower is known for its beautiful yellow color and attractive shape.',
            'image'=>'',
        ]);
    }
}
