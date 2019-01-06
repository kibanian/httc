<?php

declare(strict_types=1);

namespace App\CoreService;

use App\Card;
use Illuminate\Support\Facades\DB;
use Exception;
use Carbon\Carbon;

class CardRegistService extends CoreService {

    private $int_properties = [
        'user_id',
        'card_type',
        'made_year',
        'made_month',
        'made_date'
    ];

    private $user_id;
    private $card_type;
    private $made_year;
    private $made_month;
    private $made_date;

    public function regist()
    {
        try{
            Card::create([
                'user_id' => $this->user_id,
                'card_type' => $this->card_type,
                'made_at' => Carbon::createFromDate($this->made_year, $this->made_month, $this->made_date)
            ]);
        }catch(Exception $e){
            echo sprintf('【%s】LINE:%s %s', __METHOD__, __LINE__, $e->getMessage()).PHP_EOL;
            return false;
        }

        return true;
    }

    //二重登録不可
    public function is_already_registed()
    {
        return Card::FindByUserId($this->user_id)
            ->FindByCardType($this->card_type)
            ->exists();
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