<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cancel extends Model
{
    protected $fillable = [
        'user_id',
        'event_id',
        'cancel_fee',
        'attend_id'
    ];
    //
    public function event()
    {
        return $this->belongsTo('App\Event');
    }
    
    public function user()
    {
        return $this->belongsTo('App\Cancel');
    }

    public function attend()
    {
        return $this->belongsTo('App\Attend');
    }

    public function scopeFindByEventIdAndUserId($query, int $event_id, int $user_id)
    {
        return $query->where('event_id', $event_id)
            ->where('user_id', $user_id);
    }
    
}
