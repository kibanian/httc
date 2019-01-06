<?php

declare(strict_types=1);

namespace App\CoreService;

use Exception;
use App\Attend;
use App\Card;
use Illuminate\Support\Facades\Log;

class EventAttendService extends CoreService {
    
    private $int_properties = [
        'user_id',
        'event_id',
        'status',
        'card_count'
    ];

    private $user_id;
    private $event_id;
    private $status;
    private $card_count;

    function __construct(){
        parent::__construct();
    }

    public function attend()
    {
        try{
            $card_count = Card::FindByUserId($this->user_id)
                ->all()
                ->count();
            $attend = Attend::create([
                'user_id' => $this->user_id,
                'event_id' => $this->event_id,
                'status' => $this->status,
                'card_count' => $card_count
            ]);
        }catch(Exception $e){
            echo $e->getMessage();
            exit;
        }
    }

    //すでにイベントに参加表明済でないか確認
    public function is_double_entry(): bool
    {
        Log::debug(__METHOD__);
        log::debug('event_id:'.$this->event_id);
        log::debug('user_id:'.$this->user_id);
        return Attend::FindByEventIdAndUserId($this->event_id, $this->user_id)->exists();
    }

    public function get_event_id()
    {
        return $this->event_id;
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