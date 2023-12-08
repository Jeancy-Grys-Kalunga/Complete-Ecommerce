<?php

namespace Database\Seeders;

use App\Models\Coupon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CouponSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       
        Coupon::create([
            'code'=>'JPKM030',
            'type'=>'fixed',
            'value'=>'300',
            'status'=>'active'
        ]);

        Coupon::create([
            'code'=>'111111',
            'type'=>'percent',
            'value'=>'10',
            'status'=>'active'
        ]);
    }
}
