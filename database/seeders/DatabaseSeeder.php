<?php

namespace Database\Seeders;

use App\Models\Adress_category;
use App\Models\Decoration;
use App\Models\Dress_And_Makeup;
use App\Models\Event;
use App\Models\Food;
use App\Models\Image_last;
use App\Models\Image_user_event;
use App\Models\Last_Image;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\User;
use App\Models\Songer;
use App\Models\User_event;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'is_admin' => fake()->boolean(),


        // ]);

        $this->call([
            UserSeeder::class,
            SongerSeeder::class,
            AdressSeeder::class,
            PlaceSeeder::class,
            FoodSeeder::class,
            DecorationSeeder::class,
            CarSeeder::class,
            Dress_And_MakeupSeeder::class,
            EventSeeder::class,
            Event_CommingSeeder::class,
            Image_CommingSeeder::class,
            Image_PlaceSeeder::class,
            User_eventSeeder::class,
            quistionSeeder::class,


        ]);
    }
}
