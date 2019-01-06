<?php

declare(strict_types=1);

namespace App\CoreService;

use App\Cancel;
use App\Attend;
use App\Event;
use Illuminate\Support\Facades\DB;

class CancelRegistService extends CoreService {

    private $int_properties = [
        'event_id',
        'user_id',
        'cancel_fee'
    ];

    private $event_id;
    private $user_id;
    private $cancel_fee;

    public function regist()
    {
        try{
            $attend_id = Attend::select('id')
                ->FindByEventIdAndUserId($this->event_id, $this->user_id)
                ->first()
                ->id;
            $cancel = Cancel::create([
                'event_id' => $this->event_id,
                'user_id' => $this->user_id,
                'cancel_fee' => $this->cancel_fee,
                'attend_id' => $attend_id
            ]);
        }catch(Exception $e){
            echo sprintf('【%s】LINE:%s %s', __METHOD__, __LINE__, $e->getMessage()).PHP_EOL;
            return false;
        }

        return true;
    }

    //キャンセル済かどうか
    public function is_already_canceled()
    {
        $cancel = Cancel::FindByEventIdAndUserId($this->event_id, $this->user_id)
            ->first();
        return $cancel && $cancel->id && $cancel->attend->id;
    }

    //主催者はキャンセル不可
    public function is_host_user()
    {
        return $this->user_id == Event::find($this->event_id)->host_id;
    }

    function __set($name, $value)
    {
        try{
            if(in_array($name, $this->int_properties, true) && is_numeric($value)){
                $this->$name = $value;
            }else{
                throw new Exception(sprintf('【%s】LINE:%s %s is invalid value', __METHOD__, __LINE__, $name));
            }    
        }catch(Exception $e){
            echo sprintf('【%s】LINE:%s %s', __METHOD__, __LINE__, $e->getMessage()).PHP_EOL;
            exit;
        }
    }
}