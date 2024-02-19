<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Malhal\Geographical\Geographical;

class Supermarket extends Model
{

    use HasFactory;
    use Geographical;

    protected $guarded = [];
    protected $table = 'supermarkets';
    protected static  $kilometers = true;


    public function fournisseur()
    {
        return $this->hasOne('App\Models\User', 'id', 'user_id');
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public static function getProductByShop($slug)
    {
        return Supermarket::with('products')->where('slug', $slug)->firstOrFail();
    }

    public static function getProductBySupplie()
    {
        return Supermarket::with('fournisseur')->paginate(10);
    }

    public static function supplies ($market)
    {
        return Supermarket::with('fournisseur')->with('products')->where('id', $market)->get();
    }

    public function supermarketCategory()
    {
        return $this->hasOne(SuperMarketCategory::class, 'id', 'super_market_category_id');
    }
}
