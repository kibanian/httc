<?php

declare(strict_types=1);

namespace App\CoreService;

use Exception;
use Carbon\Carbon;

class TenniscourtFeeCalcService extends CoreService {

    private $int_properties = [
        'court_id',
        'base_fee_per_hour',
        'lighting_fee_per_hour',
        'other_fee',
        'year'
    ];

    private $court_id;
    private $year;
    private $base_fee_per_hour;
    private $lighting_fee_per_hour;
    private $other_fee;
    private $court_fees;

    function __construct()
    {
        parent::__construct();
    }

    //テニスコート料金計算、今後拡張予定
    public function calc()
    {
        $tmp = collect([]);
        for(
            $i = Carbon::create($this->year)->startOfYear();
            $i->lte(Carbon::create($this->year)->endOfYear());
            $i->addDay(1)
        ){
            $date = clone $i;
            $tmp->push([
                'court_id' => $this->court_id,
                'date' => $date->format('Y-m-d'),
                'base_fee_per_hour' => $this->base_fee_per_hour,
                'lighting_fee_per_hour' => $this->lighting_fee_per_hour,
                'other_fee' => $this->other_fee
            ]);
        }

        $this->court_fees = $tmp;
    }

    public function get_court_fees()
    {
        return $this->court_fees;
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