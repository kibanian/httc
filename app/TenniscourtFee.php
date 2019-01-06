<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class TenniscourtFee extends Model
{
    //
    protected $fillable = [
        'court_id',
        'date',
        'base_fee_per_hour',
        'lighting_fee_per_hour',
        'other_fee'
    ];

    public function tenniscourt()
    {
        return $this->belongsTo('App\Tenniscourt');
    }

    public function scopeFindByCourtId($query, int $court_id)
    {
        return $query->where('court_id', $court_id);
    }

    public function scopeFindByDate($query, string $date)
    {
        return $query->where('date', $date);
    }

    //ナイターなしのコート料金を取得
    public function scopeSelectSumOfDaytimeCourtFee($query)
    {
        return $query->select(DB::raw('(base_fee_per_hour + other_fee) as court_fee'));
    }

    //ナイターのコート料金を取得
    public function scopeSelectSumOfNighttimeCourtFee($query)
    {
        return $query->select(DB::raw('(base_fee_per_hour + lighting_fee_per_hour + other_fee) as court_fee'));
    }
}
