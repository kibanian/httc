<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FeeType extends Model
{
    //
    public function event()
    {
        return $this->belongsTo('App\Event', 'fee_type');
    }
}
