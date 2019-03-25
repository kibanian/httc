<?php

declare(strict_types=1);

namespace App\CoreService\Attend;

use App;
use Illuminate\Support\Facades\Log;
use Exception;

class AttendFeeFacade extends AttendCoreService
{

    private $int_properties = [
        'event_id'
    ];

    private $event_id;

    private $afc;

    function __construct()
    {
        $this->afc = App::make('AttendFeeFacade');
    }

    public function main()
    {
        $this->afc->event_id = $this->event_id;
        $this->afc->set_event_data();
        $this->afc->get_court_fee();
        $this->afc->get_attenders_count();

        $fee_type = $this->get_fee_type();
        if($fee_type == 1)
        {
            $this->afc->split_court_fee_plus_three_hundred_yen();
        }
        else
        {
            return false;
        }
        if(!$this->afc->update_attend_fee()){ return false; }

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