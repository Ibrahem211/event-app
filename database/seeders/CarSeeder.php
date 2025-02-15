<?php

namespace Database\Seeders;

use App\Models\Car;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        Car::create([
            'name' => 'mercedes',
            'parent_id' => NULL,
            'price' => 200,
            'image' => '',
        ]);
        Car::create([
            'name' => 'hyundai',
            'parent_id' => NULL,
            'price' => 200,
            'image' => '',
        ]);

        Car::create([
            'name' => 'limuzen',
            'parent_id' => NULL,
            'price' => 200,
            'image' => '',
        ]);

        Car::create([
            'name' => 'BMW',
            'parent_id' => NULL,
            'price' => 200,
            'image' => '',
        ]);

        Car::create([
            'name' => 'Maybach GLS',
            'parent_id' => 1,
            'price' => 650,
            'description' => 'Maybach Display Style The fully digital cabin offers a variety of different display styles. The new Mercedes-Maybach GLS is the first model to display the specially designed “Maybach” style. The information appears in exclusive shades of rose gold and navy blue, accented with metal decorations for the numerals, screws and tips.',
            'image' => '',
        ]);

        Car::create([
            'name' => 'mercedes E 300',
            'parent_id' => 1,
            'price' => 340,
            'description' => 'A decent-looking car with a bright silver color. It has a trailer equipped with an air conditioning system, a Bluetooth recorder with an AUX connection, in addition to luxurious leather seats, a multimedia touch screen, a touch door lock system, and power.
            Steering wheel, dual-zone automatic air conditioning, smart parking system, remote control, cruise control, and electric seats.',
            'image' => '',
        ]);

        Car::create([
            'name' => 'GLE 350 4Matic 2020',
            'parent_id' => 1,
            'price' => 540,
            'description' => 'A luxury car from the mountain system, high off the ground and containing a luxurious cabin with electric seats made of luxurious leather, Bluetooth registered, with a D13 air conditioning system.
            Equipped with a sunroof and four 12:5 inch Samsung screens
            Stuffed leather seats are comfortable for long sitting',
            'image' => '',
        ]);

        Car::create([
            'name' => 'G Class',
            'parent_id' => 1,
            'price' => 1230,
            'description' => 'The Mercedes G-Class is one of the most famous and important luxury SUVs in the global markets.
            Mercedes provides the ability to enhance the interior specifications with many optional equipment, most notably ambient lighting for the air conditioner vents,
             a 3D surround sound system from Burmester, star lighting on the car roof, and many embroidery options. Luxurious nappa leather with multiple colors upon request.',
            'image' => '',
        ]);

        Car::create([
            'name' => 'Hyundai Afanti 2006',
            'parent_id' => 2,
            'price' => 220,
            'description' => 'For a car characterized by good performance and stability at high speeds,
             and a non-rigid furniture that makes you feel comfortable riding, especially in bumps, unpaved roads, and traveling long distances, and its spacious and comfortable interior cabin that isolates external noise, and contains many equipment such as an elegant and organized dashboard that does not distract the driver attention in obtaining information.
             While driving, you can control its lighting, air conditioning, electric windows, radio cassette, Bluetooth, USB port, AUX, CD player,
             four-speaker sound system, power steering with paddles to control the lights and wipers, and reading lights.',
            'image' => '',
        ]);

        Car::create([
            'name' => 'Hyundai Elantra ',
            'parent_id' => 2,
            'price' => 570,
            'description' => 'An economical luxury car with a heated steering wheel and a system to remove fog from inside the car
            An electronic control panel to control the entire car by touch
            A screen from Hyundai to enjoy listening to songs and watching YouTube
            Four recorders distributed in the car with stereo sound that is comfortable for hearing and reduces noise',
            'image' => '',
        ]);

        Car::create([
            'name' => 'Hyundai Sonata ',
            'parent_id' => 2,
            'price' => 480,
            'description' => 'A luxury car with a large and comfortable cabin,
            Soft foam seats covered in natural leather.
            It contains an anti-concussion system with a clear audio recorder and an AUX compass.
            Comfortable hydraulic steering system for the driver with sunroof to enjoy the air',
            'image' => '',
        ]);

        Car::create([
            'name' => 'Limuzen B.E',
            'parent_id' => 3,
            'price' => 1640,
            'description' => 'A luxury car, with a large cabin that can accommodate seven people,
            complete refrigeration service with a refrigerator for storing drinks, soft feather seats with an automatic massage system.',
            'image' => '',
        ]);

        Car::create([
            'name' => 'Limuzen W',
            'parent_id' => 3,
            'price' => 1925,
            'description' => 'A luxury car that is quite large and can accommodate up to 6 people
            Equipped with an S612 air conditioning system, a drinks refrigerator, and a table installed to place snacks
            A ceiling decorated with stars in the sky and a comfortable stereo that filters out the loud noise
            The cabin is isolated from the drivers room to ensure privacy',
            'image' => '',

        ]);

        Car::create([
            'name' => 'BMW M 5',
            'parent_id' => 4,
            'price' => 772,
            'description' => 'A sports car with an attractive, youthful appearance. It has a spacious cabin with luxurious leather seats.
            High-quality air conditioning system with radiators to absorb vibrations and feel comfortable.
            Adequately sized screen with attractive stereo sound',
            'image' => '',
        ]);

        Car::create([
            'name' => 'BMW 507',
            'parent_id' => 4,
            'price' => 345,
            'description' => 'A classic car with a high-quality air conditioning system and comfortable foam seats
            Four appropriate-sized screens that can be connected via Bluetooth
            Recorded with bazooka to filter any type of noise
            Femi prevents heat on window glass',
            'image' => '',
        ]);
    }
}
