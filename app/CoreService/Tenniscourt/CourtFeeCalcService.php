<?php

declare(strict_types=1);

namespace App\CoreService\Tenniscourt;

use App\TenniscourtFee;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class CancelFeeCalcService extends TenniscourtCoreService
{

    //コート料金を取得
    //練習開始時間帯によって場合分け
    public function get_court_fee(string $start_datetime, string $end_datetime, int $court_id)
    {
        $court_fee_per_hour = (($datetime->hour >= self::SEVENTEENTH)
            ? TenniscourtFee::SelectSumOfNighttimeCourtFee()
            : TenniscourtFee::SelectSumOfDaytimeCourtFee())
            ->FindByCourtId($court_id)
            ->FindByDate(Carbon::parse($start_datetime)->format('Y-m-d'))
            ->first()
            ->court_fee;
        Log::debug(sprintf('【%s】LINE:%s court_fee_per_hour:%s', __METHOD__, __LINE__, $court_fee_per_hour));
        $practice_term = Carbon::parse($end_datetime)
            ->diffInHours(Carbon::parse($start_datetime));
        Log::debug(sprintf('【%s】LINE:%s practice_term:%s', __METHOD__, __LINE__, $practice_term));
        //1時間あたりの料金で料金テーブルから取得するので、練習時間を掛ける
        $court_fee = $court_fee_per_hour * $practice_term;
        Log::debug(sprintf('【%s】LINE:%s court_fee:%s', __METHOD__, __LINE__, $court_fee));
        return $court_fee;
    }

    public function get_attenders_count(int $event_id)
    {
        $attenders_count = Attend::FindByEventId($event_id)
            ->FindByApproved()
            ->count();
        Log::debug(sprintf('【%s】LINE:%s attenders_count:%s', __METHOD__, __LINE__, $attenders_count));
        //新規参加者分を加算
        $attenders_count += Event::find($event_id)->attended_newcomer_count;
        Log::debug(sprintf('【%s】LINE:%s attenders_count:%s', __METHOD__, __LINE__, $attenders_count));

        return $attenders_count;
    }
}