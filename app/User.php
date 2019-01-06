<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function attend()
    {
        return $this->hasMany('App\Attend');
    }

    public function card()
    {
        return $this->hasMany('App\Card');
    }

    public function event()
    {
        return $this->hasMany('App\Event', 'host_id');
    }

    public function cancel()
    {
        return $this->hasMany('App\Cancel')
    }
}
