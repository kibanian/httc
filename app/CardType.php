<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CardType extends Model
{

    protected $fillable = [
        'type'
    ];
    //
    public function card()
    {
        return $this->hasMany('App\Card', 'card_type');
    }
}
