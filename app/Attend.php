<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Attend extends Model
{
    /**
     * statusについて
     * 1:参加承認待ち
     * 2:参加確定
     * 3:キャンセル待ち
     * 4:キャンセル済
     * 
     */

    const APPLIED = 1;
    const APPROVED = 2;
    const WAITING = 3;
    const CANCELED = 4;

    protected $fillable = [
        'user_id',
        'event_id',
        'status',
        'card_count'
    ];
    
    //
    public function event()
    {
        return $this->beongsTo('App\Event');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function cancel()
    {
        return $this->hasOne('App\Cancel');
    }

    public function scopeFindByEventIdAndUserId($query, int $event_id, int $user_id)
    {
        return $query->where('event_id', $event_id)
            ->where('user_id', $user_id);
    }

    public function scopeFindByUserIds($query, array $user_ids)
    {
        return $query->whereIn('user_id', $user_ids);
    }

    //練習に参加表明しているユーザーを一覧表示する
    public function scopeFindByEventId($query, int $event_id)
    {
        return $query->where('event_id', $event_id);
    }

    //参加表明済
    public function scopeFindApplied($query)
    {
        return $query->where('status', self::APPLIED);
    }

    //参加確定
    public function scopeFindApproved($query)
    {
        return $query->where('status', self::APPROVED);
    }

    //キャンセル待ち
    public function scopeFindWaiting($query)
    {
        return $query->where('status', self::WAITING);
    }

    //キャンセル済
    public function scopeFindCanceled($query)
    {
        return $query->where('status', self::CANCELED);
    }
    

    //参加表明または参加確定またはキャンセル待ち
    public function scopeFindAppliedOrApprovedOrWaiting($query)
    {
        return $query->whereIn('status', [self::APPLIED, self::APPROVED, self::WAITING]);
    }

    //参加表明しているユーザーの中で、練習当日から7日前までに参加表明したユーザーを取得
    public function scopeFindAppliedUntilSevenDaysAgo($query, string $datetime)
    {
        return $query->whereDate('created_at', '<', $datetime);
    }

    //参加表明しているユーザーの中で、練習当日から7日前以降に参加表明したユーザーを取得
    public function scopeFindAppliedSinceSevenDaysAgo($query, string $datetime)
    {
        return $query->whereDate('created_at', '>=', $datetime);
    }

    public function scopeOrderByCardCountDesc($query)
    {
        return $query->orderBy('card_count', 'desc');
    }

    public function scopeOrderByUpdatedAtAsc($query)
    {
        return $query->orderBy('created_at', 'asc');
    }

}
