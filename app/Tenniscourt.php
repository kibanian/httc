<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tenniscourt extends Model
{
    protected $fillable = [
        'court_name',
        'zipcode',
        'address'
    ];
    //
    public function event()
    {
        return $this->belongsTo('App\Event', 'court_id');
    }

    public function tenniscourtfee()
    {
        return $this->hasMany('App\TenniscourtFee', 'court_id');
    }
}
