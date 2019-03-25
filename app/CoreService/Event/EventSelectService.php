<?php

declare(strict_types=1);

namespace App\CoreService\Event;

use App\Event;
use App\Attend;
use Exception;
use App;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

//イベント取得
class EventSelectService extends EventCoreService
{
    private $int_properties = [
        'event_id'
    ];

    private $as;
    private $event_id;
    private $events;

    function __construct()
    {

    }

    public function main($id=null)
    {
        $this->events = [];
        try{
            //イベントIDの指定がなければ全件取得
            foreach(Event::all() as $event){
                
                $this->events[] = [
                    'id' => $event->id,
                    'start_date' => Carbon::parse($event->start_datetime)->format('Y-m-d'),
                    'start_time' => Carbon::parse($event->start_datetime)->format('H:i:s'),
                    'end_time' => Carbon::parse($event->end_datetime)->format('H:i:s'),
                    'court_name' => $event->tenniscourt->court_name,
                    'address' => $event->tenniscourt->address,
                    'zipcode' => $event->tenniscourt->zipcode,
                    'member_limit' => $event->member_limit,
                    'court_number' => $event->court_number,
                    //'fee_type' => $event->fee_type->name,
                    'host_name' => $event->user->name,
                    'newcomer_count' => $event->newcomer_count,
                    'status' => $event->status
                ];
            }

            Log::debug(sprintf('【%s】LINE:%s events:%s', __METHOD__, __LINE__, var_export($this->events, true)));

        }catch(Exception $e){
            Log::debug($e);
            echo $e;
        }

        return $this->events;
    }

    public function select_all()
    {
        $this->events = [];
        try{
            //イベントIDの指定がなければ全件取得
            foreach(Event::all() as $event){
                $this->events[] = [
                    'id' => $event->id,
                    'start_date' => Carbon::parse($event->start_datetime)->format('Y-m-d'),
                    'start_time' => Carbon::parse($event->start_datetime)->format('H:i:s'),
                    'end_time' => Carbon::parse($event->end_datetime)->format('H:i:s'),
                    'court_name' => $event->tenniscourt->court_name,
                    'address' => $event->tenniscourt->address,
                    'zipcode' => $event->tenniscourt->zipcode,
                    'member_limit' => $event->member_limit,
                    'court_number' => $event->court_number,
                    //'fee_type' => $event->fee_type->name,
                    'host_name' => $event->user->name,
                    'newcomer_count' => $event->newcomer_count,
                    'status' => $event->status
                ];
            }

            Log::debug(sprintf('【%s】LINE:%s events:%s', __METHOD__, __LINE__, var_export($this->events, true)));

        }catch(Exception $e){
            Log::debug($e);
            echo $e;
        }

        return $this->events;
    }

    public function select_one()
    {
        try{
            $event = Event::where('id', $this->event_id)->first();

            $event_detail = [
                'id' => $event->id,
                'start_date' => Carbon::parse($event->start_datetime)->format('Y-m-d'),
                'start_time' => Carbon::parse($event->start_datetime)->format('H:i'),
                'end_time' => Carbon::parse($event->end_datetime)->format('H:i'),
                'court_name' => $event->tenniscourt->court_name,
                'address' => $event->tenniscourt->address,
                'zipcode' => $event->tenniscourt->zipcode,
                'member_limit' => $event->member_limit,
                'court_number' => $event->court_number,
                //'fee_type' => $event->fee_type->name,
                'host_name' => $event->user->name,
                'newcomer_count' => $event->newcomer_count,
                'status' => $event->status,
                'approved' => $this->get_attends('approved'),
                'waiting' => $this->get_attends('waiting'),
                'canceled' => $this->get_attends('canceled')
            ];

        }catch(Exception $e){
            Log::debug($e);
            echo $e;
            $event_detail = [];
        }

        Log::debug(sprintf('【%s】LINE:%s event_detail:%s', __METHOD__, __LINE__, var_export($event_detail, true)));
        return $event_detail;
    }

    //タイプ別に参加者を取得する
    private function get_attends(string $type){
        $attends = Attend::FindByEventId($this->event_id);
        if($type == 'approved'){
            $attends->FindApproved();
        }elseif($type == 'waiting'){
            $attends->FindWaiting();
        }elseif($type == 'canceled'){
            $attends->FindCanceled();
        }else{
            return [];
        }
        $attend_data = $attends->get()->map(function($item){
            return [
                'user_id' => $item->user_id,
                //'user_name' => $item->user->name,
                'status' => $item->status,
                'card_count' => $item->card_count,
                'created_at' => Carbon::parse($item->created_at)->format('Y-m-d H:i'),
                'updated_at' => Carbon::parse($item->updated_at)->format('Y-m-d H:i')
            ];
        })->toArray();

        Log::debug(sprintf('【%s】LINE:%s event_detail:%s', __METHOD__, __LINE__, var_export($attend_data, true)));
        return $attend_data;
    }


    function __set($name, $value)
    {
        try{
            if(in_array($name, $this->int_properties, true) && is_int($value)){
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