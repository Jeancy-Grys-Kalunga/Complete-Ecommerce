<?php

namespace App\Models;

use App\User;
use App\Models\Shop;
use App\Models\Supermarket;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Fournisseur extends Model
{
    protected $guarded = [];
    use HasFactory;


    //    /**
    //      * boot permet de récupérer l'id de l'utilisateur
    //      * lors de l'insertion et d'édition
    //      *
    //      * @return void
    //      */
    //     public static function boot()
    //     {
    //         parent::boot();

    //         self::creating(function ($fournisseur) {
    //             $fournisseur->user()->associate(auth()->user()->id);
    //         });


    //     }



    public function supermarket()
    {
        return $this->belongsTo(Supermarket::class,);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
