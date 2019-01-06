<?php

declare(strict_types=1);

namespace App\CoreService;

use Exception;
use Carbon\Carbon;

class DateTimeService extends CoreService {

    function __construct()
    {

    }

    public function set_datetime(int $year, int $month, int $date, int $hour, int $minute): Carbon
    {
        try{
           $datetime = Carbon::create($year, $month, $date, $hour, $minute, '00');
        }catch(Exception $e){
            echo $e->getMessage();
            exit;
        }
        return $datetime;
    }
}