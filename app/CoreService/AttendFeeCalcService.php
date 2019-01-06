<?php

declare(strict_types=1);

namespace App\CoreService;

use App\Attend;
use App\Event;
use App\TenniscourtFee;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Exception;

class AttendFeeCalcService extends CoreService {

    private $int_properties = [
        'event_id'
    ];

    private $event_id;
    private $attend_fee;
    private $court_fee;
    private $attenders_count;

    const THREE_HUNDRED_YEN = 300;

    public function set_event_data()
    {
        $this->event_data = Event::find($this->event_id);

        Log::debug(sprintf('【%s】LINE:%s event_data:%s', __METHOD__, __LINE__, $this->event_data));

        $this->start_datetime = $this->event_data->start_datetime;
        $this->end_datetime = $this->event_data->end_datetime;
        $this->court_id = $this->event_data->court_id;
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

    //参加費タイプ取得
    public function get_fee_type()
    {
        return $this->event_data->fee_type;
    }

    //練習の参加者数を取得
    public function get_attenders_count()
    {
        $this->attenders_count = $this->cfc->get_attenders($this->event_id);
    }

    //コート代の人数割り＋300円
    public function split_court_fee_plus_three_hundred_yen()
    {
        $this->attend_fee = ($this->court_fee / $this->get_attenders_count) + self::THREE_HUNDRED_YEN;
    }

    public function update_attend_fee()
    {
        try{
            Attend::FindByEventId($this->event_id)
                ->FindByApproved()
                ->update(['fee' => $this->attend_fee]);
        }catch(Exception $e){
            echo sprintf('【%s】LINE:%s %s', __METHOD__, __LINE__, $e->getMessage()).PHP_EOL;
            return false;
        }

        return true;
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