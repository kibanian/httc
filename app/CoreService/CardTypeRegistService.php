<?php

declare(strict_types=1);

namespace App\CoreService;

use App\Card;
use App\CardType;
use Illuminate\Support\Facades\DB;

class CardTypeRegistService extends CoreService {

    private $str_properties = [
        'type'
    ];

    private $type;

    public function regist()
    {
        try{
            CardType::create([
                'type' => $this->type
            ]);
        }catch(Exception $e){
            echo sprintf('【%s】LINE:%s %s', __METHOD__, __LINE__, $e->getMessage()).PHP_EOL;
            return false;
        }

        return true;
    }

    function __set($name, $value)
    {
        try{
            if(in_array($name, $this->str_properties, true) && is_string($value)){
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