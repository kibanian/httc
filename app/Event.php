<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Event extends Model
{
    /**
     * ステータスについて
     * 1:参加申請可
     * 2:中止
     * 
     */
    const APPLIABLE = 1;
    const CANCELED = 2;

    protected $fillable = [
        'start_datetime',
        'end_datetime',
        'court_id',
        'member_limit',
        'court_number',
        'fee_type',
        'host_id',
        'newcomer_count',
        'status',
        'attended_newcomer_count'
    ];
    //
    public function attends()
    {
        return $this->hasMany('App\Attend');
    }

    public function tenniscourt()
    {
        return $this->hasOne('App\Tenniscourt', 'id', 'court_id');
    }

    public function cancels()
    {
        return $this->hasMany('App\Cancel');
    }

    public function fee_type()
    {
        return $this->hasOne('App\FeeType', 'id', 'name');
    }

    public function user()
    {
        return $this->belongsTo('App\User', 'host_id');
    }

    public function scopeFindById($query, int $event_id)
    {
        return $query->where('id', $event_id);
    }

    //申請可能なイベント
    public function scopeFindAppliable($query)
    {
        return $query->where('status', self::APPLIABLE);
    }

    public function scopeFindBeforeStarting($query)
    {
        return $query->where('start_datetime', '>', Carbon::now());
    }

    
}
