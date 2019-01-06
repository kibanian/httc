<?php

declare(strict_types=1);

namespace App\CoreService;

use Exception;
use App\TenniscourtFee;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;

class TenniscourtFeeRegistService extends CoreService {

    private $array_properties = [
        'court_fees',
    ];

    private $court_fees;

    function __construct()
    {
        parent::__construct();
    }

    public function regist()
    {
        $court_fees = $this->court_fees;
        DB::transaction(function() use($court_fees){
            $court_fees->each(function($item){
                TenniscourtFee::create([
                    'court_id' => $item['court_id'],
                    'date' => $item['date'],
                    'base_fee_per_hour' => $item['base_fee_per_hour'],
                    'lighting_fee_per_hour' => $item['lighting_fee_per_hour'],
                    'other_fee' => $item['other_fee']
                ]);
            });
        });

        return true;
    }

    function __set($name, $value)
    {
        try{
            if(in_array($name, $this->array_properties, true) && ($value instanceof Collection)){
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