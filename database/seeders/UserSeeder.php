<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        User::create([
            'name'=>'mark',
            'email'=>'mark@gmail.com',
            'PhoneNumber'=>'097476586',
            'password'=>Hash::make('123'),
            'image'=>'',
            'is_admin'=>'1',

        ]);
        User::create([
            'name'=>'ghith',
            'email'=>'ghith@gmail.com',
            'PhoneNumber'=>'0976674883',
            'password'=>Hash::make('12345'),
            'image'=>'',
            'is_admin'=>'0',

        ]);
        User::create([
            'name'=>'ibrahem',
            'email'=>'ibrahem@gmail.com',
            'PhoneNumber'=>'0975803643',
            'password'=>Hash::make('123'),
            'image'=>'',
            'is_admin'=>'1',

        ]);
        User::create([
            'name'=>'tamara',
            'email'=>'tamara@gmail.com',
            'PhoneNumber'=>'099975254',
            'password'=>Hash::make('12345'),
            'image'=>'',
            'is_admin'=>'0',

        ]);
        User::create([
            'name'=>'mohammad',
            'email'=>'mohammad@gmail.com',
            'PhoneNumber'=>'0974667880',
            'password'=>Hash::make('12345'),
            'image'=>'',
            'is_admin'=>'0',

        ]);

        User::create([
            'name'=>'shahed',
            'email'=>'shahed@gmail.com',
            'PhoneNumber'=>'0974667881',
            'password'=>Hash::make('12345'),
            'image'=>'',
            'is_admin'=>'0',

        ]);

    }
}
