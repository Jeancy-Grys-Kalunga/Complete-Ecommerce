<?php

namespace Database\Seeders;

use App\Models\User;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        User::create([
            'name'=>'Admin',
            'email'=>'admin@gmail.com',
            'password'=>Hash::make('111111'),
            'role'=> 2,
            'status'=>'active'
        ]);

        User::create([
            'name'=>'Fournisseur',
            'email'=>'fournisseur@gmail.com',
            'password'=>Hash::make('111111'),
            'role'=> 1,
            'status'=>'active'
        ]);

        User::create([
            'name'=>'User',
            'email'=>'user@gmail.com',
            'password'=>Hash::make('111111'),
            'role'=> 0,
            'status'=>'active'
        ]);
    }
}
