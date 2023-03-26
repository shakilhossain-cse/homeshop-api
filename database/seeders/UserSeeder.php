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
        //create user 
        User::create([
            'name' => "shakil",
            'email' => "shakil@gmail.com",
            'password' => Hash::make('shakil'),
            'role'=>'admin'
        ]);
        User::create([
            'name' => "dina",
            'email' => "dina@gmail.com",
            'password' => Hash::make('shakil'),
            'role'=>'member'
        ]);
    }
}
