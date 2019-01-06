<?php

declare(strict_types=1);

namespace App\CoreService;

use App\Event;
use Exception;
use App;

//イベント登録
class EventRegistService extends CoreService
{
    private $datetime_properties = [
        'start_year',
        'start_month',
        'start_date',
        'start_hour',
        'start_minute',
        'end_year',
        'end_month',
        'end_date',
        'end_hour',
        'end_minute'
    ];
    private $int_properties = [
        'start_year',
        'start_month',
        'start_date',
        'start_hour',
        'start_minute',
        'end_year',
        'end_month',
        'end_date',
        'end_hour',
        'end_minute',
        'court_id',
        'member_limit',
        'court_number',
        'fee_type',
        'host_id',
        'newcomer_count',
        'status'
    ];

    private $start_datetime;
    private $end_datetime;
    private $start_year;
    private $start_month;
    private $start_date;
    private $start_hour;
    private $start_minute;
    private $end_year;
    private $end_month;
    private $end_date;
    private $end_hour;
    private $end_minute;
    private $court_id;
    private $member_limit;
    private $court_number;
    private $fee_type;
    private $host_id;
    private $newcomer_count = 0;
    private $status = 1;

    private $dt;

    function __construct()
    {
        parent::__construct();

        $this->dt = App::make('DateTime');
    }

    public function regist()
    {
        try{
            $event = Event::create([
                'start_datetime' => $this->start_datetime,
                'end_datetime' => $this->end_datetime,
                'court_id' => $this->court_id,
                'member_limit' => $this->member_limit,
                'court_number' => $this->court_number,
                'fee_type' => $this->fee_type,
                'host_id' => $this->host_id,
                'newcomer_count' => $this->newcomer_count,
                'status' => $this->status
            ]);
        }catch(Exception $e){
            echo $e->getMessage();
            exit;
        }
    }

    public function set_start_datetime()
    {
        $this->start_datetime = $this->dt->set_datetime(
            $this->start_year,
            $this->start_month,
            $this->start_date,
            $this->start_hour,
            $this->start_minute
        );
    }

    public function set_end_datetime()
    {
        $this->end_datetime = $this->dt->set_datetime(
            $this->end_year,
            $this->end_month,
            $this->end_date,
            $this->end_hour,
            $this->end_minute
        );
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