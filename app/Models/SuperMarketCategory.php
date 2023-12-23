<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuperMarketCategory extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function supermarkets()
    {
        return $this->hasMany(Supermarket::class,'super_market_category_id','id')->where('status','active');
    }
}
