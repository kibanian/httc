<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    protected $fillable = [
        'user_id',
        'made_at',
        'card_type'
    ];
    //
    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function card_type()
    {
        return $this->belongsTo('App\CardType');
    }

    public function scopeFindByUserId($query, int $user_id)
    {
        return $query->where('user_id', $user_id);
    }

    public function scopeFindByCardType($query, int $card_type)
    {
        return $query->where('card_type', $card_type);
    }
}
