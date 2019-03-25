<?php

declare(strict_types=1);

namespace App\CoreService\Attend;

use App\Attend;
use Illuminate\Support\Facades\Log;
use Exception;

class AttendSelectService extends AttendCoreService
{

    private $int_properties = [
        'event_id'
    ];

    private $event_id;
    private $attend;

    get_user_name()
    {
        return $attend->user->name
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