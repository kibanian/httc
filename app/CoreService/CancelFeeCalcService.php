<?php

declare(strict_types=1);

namespace App\CoreService;

use App\Attend;
use App\Event;
use App\TenniscourtFee;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use App;

class CancelFeeCalcService extends CoreService {

    private $int_properties = [
        'event_id',
        'user_id'
    ];

    const ONE_HUNDRED_YEN = 100;
    const TWO_HUNDRED_YEN = 200;
    const SEVENTEENTH = 17;

    private $event_id;
    private $user_id;
    private $attenders_count;
    private $attenders_count_except_for_canceled_one;
    private $start_datetime;
    private $end_datetime;
    private $member_limit;
    private $event_data;
    private $court_id;
    private $court_fee;

    private $cfc;

    function __construct()
    {
        parent::__construct();

        $this->cfc = App::make('CourtFeeCalc');
    }

    //キャンセルしたイベントの参加人数
    public function get_attenders_count()
    {
        $this->attenders_count = $this->cfc->get_attenders_count();
    }

    public function set_event_data()
    {
        $this->event_data = Event::find($this->event_id);

        Log::debug(sprintf('【%s】LINE:%s event_data:%s', __METHOD__, __LINE__, $this->event_data));

        $this->start_datetime = $this->event_data->start_datetime;
        $this->end_datetime = $this->event_data->end_datetime;
        $this->member_limit = $this->event_data->member_limit;
        $this->court_id = $this->event_data->court_id;
    }

    //イベント開始日時から5日を切った状態でキャンセルをしているか
    public function is_canceled_in_five_days_from_start_datetime(): bool
    {
        return Carbon::parse($this->start_datetime)
            ->lt(Carbon::now()->addDay('+5 days'));
    }

    //参加者数が参加上限人数の8分の7より多いか
    public function more_attenders_than_seven_eights_of_limit()
    {
       return $this->attenders_X_eights_of_limit(7, true);
    }

    //参加者数が参加上限人数の8分の5より多いか
    public function more_attenders_than_five_eights_of_limit()
    {
        return $this->attenders_X_eights_of_limit(5, true);
    }

    //参加者数が参加上限人数の半分以下か
    public function less_attenders_than_half_of_limit()
    {
        return $this->attenders_of_x_eights_of_limit(4);
    }

    //コート料金を取得
    //練習開始時間帯によって場合分け
    public function get_court_fee()
    {
        $this->court_fee = $this->cfc->get_court_fee(
            $this->start_datetime,
            $this->end_datetime,
            $this->court_id
        );
    }

    //コート代を参加者の人数割りにする
    //百円単位で切り上げ
    public function divide_court_fee_by_attenders_count()
    {

        return round(
            ($this->court_fee / ($this->attenders_count - 1)),
            -2,
            PHP_ROUND_HALF_UP
        );
    }

    //比較計算の実行部分
    private function attenders_X_eights_of_limit(int $x, bool $more_than)
    {
        return $more_than
        ? ( $this->attenders_count >= ($this->member_limit * $x / 8) )
        : ( $this->attenders_count <= ($this->member_limit * $x / 8) );
        
    }

    function __set($name, $value)
    {
        try{
            if(in_array($name, $this->int_properties, true) && is_numeric($value)){
                $this->$name = $value;
            }else{
                throw new Exception(sprintf('【%s】LINE:%s %s is invalid value', __METHOD__, __LINE__, $name).PHP_EOL);
            }    
        }catch(Exception $e){
            echo sprintf('【%s】LINE:%s %s', __METHOD__, __LINE__, $e->getMessage()).PHP_EOL;
            exit;
        }
    }
}