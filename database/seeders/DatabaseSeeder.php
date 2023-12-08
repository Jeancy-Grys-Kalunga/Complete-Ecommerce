<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Supermarket;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        $this->call(SettingTableSeeder::class);
        $this->call(CouponSeeder::class);
        $this->call(UsersTableSeeder::class);

        $users = User::factory(5)->create();
        $shops = Supermarket::factory(3)->create([
            'user_id' => $users->random(1, 3)->first()->id,
        ]);

    }
}
