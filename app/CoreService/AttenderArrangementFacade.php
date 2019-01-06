<?php

namespace App\CoreService;

use App;
use Illuminate\Support\Facades\Log;

class AttenderArrangementFacade
{
    private $aa;
    private $cfc;
    private $event_id;
    private $user_id;

    private $int_properties = [
        'event_id',
        'user_id'
    ];


    public function __construct()
    {
        $this->aa = App::make('AttenderArrangement');
        $this->cfc = App::make('CancelFeeCalc');
    }

    public function main()
    {
        $this->aa->event_id = $this->event_id;
        if(!$this->aa->set_event_data()){
            Log::debug('old event');
            return false;
        }
        $this->aa->set_applied_data();

        foreach([true, false] as $is_until)
        {
            //最初は7日以上前に参加表明したユーザーをチェック、
            //二度目は、7日以内に参加表明したユーザーをチェックするようにする
            $this->aa->set_attenders($is_until);

            $this->aa->set_vacancy_count($is_until);

            //参加希望者がいなければ、処理しない
            if(!$this->aa->has_attenders()){ continue; }

            //主催者を必ず先頭に配置
            if($this->aa->has_host_user()){ $this->aa->arrange_host_user($is_until); }

            //イベントの参加人数上限に合わせて、参加者を削る
            $this->aa->pop_attender_until_limit_number();

            //新規参加者の数だけ、参加者を削る
            if($is_until){ $this->aa->replace_attender_with_newcomer(); }

            //参加者として残ったユーザーを参加確定者にする
            if(!$this->aa->approve()){ return false; }

            //残りの参加希望者をキャンセル待ちにする
            if(!$this->aa->waiting()){ return false; }
        }

        return true;
    }

    public function cancel()
    {
        $this->aa->event_id = $this->event_id;
        $this->aa->user_id = $this->user_id;
        
        return $this->aa->cancel();
    }

    function __set($name, $value)
    {
        try{
            if(in_array($name, $this->int_properties, true) && is_numeric($value)){
                $this->$name = $value;
            }else{
                throw new Exception($name.' is invalid value');
            }    
        }catch(Exception $e){
            echo $e->getMessage();
            exit;
        }
    }

    
    
}