<?php

declare(strict_types=1);

namespace App\CoreService\Cancel;

use App;
use Illuminate\Support\Facades\Log;
use Exception;

class CancelFacade extends CancelCoreService
{

    private $cfc;
    private $cr;
    private $aaf;
    private $event_id;
    private $user_id;

    private $int_properties = [
        'event_id',
        'user_id'
    ];

    function __construct()
    {
        $this->cfc = App::make('CancelFeeCalc');
        $this->cr = App::make('CancelRegist');
        $this->aaf = App::make('AttenderArrangementFacade');
    }

    public function main()
    {
        $this->cfc->event_id = $this->cr->event_id
            = $this->aaf->event_id
            = $this->event_id;
        $this->cfc->user_id = $this->cr->user_id
            = $this->aaf->user_id
            = $this->user_id;

        //多重キャンセル不可
        if($this->cr->is_already_canceled())
        {
            Log::debug(sprintf('【%s】LINE:%s Already Canceled.', __METHOD__, __LINE__));
            echo sprintf('【%s】LINE:%s Already Canceled.'.PHP_EOL, __METHOD__, __LINE__);
            return;
        }

        //主催者はキャンセル不可
        if($this->cr->is_host_user())
        {
            Log::debug(sprintf('【%s】LINE:%s Cancel Of Host User Is Not Allowed.', __METHOD__, __LINE__));
            echo sprintf('【%s】LINE:%s Cancel Of Host User Is Not Allowed.'.PHP_EOL, __METHOD__, __LINE__);
            return;
        }

        $this->cfc->set_event_data();
        //参加者数を取得
        $this->cfc->get_attenders_count();

        //キャンセルが開催予定日時まで5日を切ってのものだった場合、
        //キャンセル料が発生
        $cancel_fee = $this->cfc->is_canceled_in_five_days_from_start_datetime()
        ? $this->calc_cancel_fee() : 0;
        Log::debug(sprintf('【%s】LINE:%s cancel_fee:%s', __METHOD__, __LINE__, $cancel_fee));

        $this->cr->cancel_fee = $cancel_fee;
        
        //キャンセルデータを登録
        if(!$this->cr->regist()){ return false; }
        
        //参加者データのステータス更新
        if(!$this->aaf->cancel()){ return false; }

        //参加者を修正する
        if(!$this->aaf->main()){ return false; }

        return true;
    }

    //キャンセル料計算
    private function calc_cancel_fee()
    {
        //キャンセルした人も含めて、参加者数が参加上限人数の8分の7以上だった場合
        if($this->cfc->more_attenders_than_seven_eights_of_limit()){
            //キャンセル料は100円
            return $this->cfc::ONE_HUNDRED_YEN;
        }
        //キャンセルした人も含めて、参加者数が参加上限人数の8分の5以上だった場合
        elseif($this->cfc->more_attenders_than_five_eights_of_limit()){
            //キャンセル料は200円
            return $this->cfc::TWO_HUNDRED_YEN;
        }
        //参加者数がそれ以下の人数の場合
        else{
            //キャンセルした本人を除いた参加者の数で、コート代を割った金額
            //ただし、百円単位で切り上げ
            $this->cfc->get_court_fee();
            return $this->cfc->divide_court_fee_by_attenders_count();
        }
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