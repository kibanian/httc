<?php

declare(strict_types=1);

namespace App\CoreService;

use Exception;
use App\Tenniscourt;
use Illuminate\Support\Facades\DB;

class TenniscourtRegistService extends CoreService {

    private $int_properties = [
        'zipcode'
    ];

    private $str_properties = [
        'court_name',
        'address'
    ];

    private $court_name;
    private $zipcode;
    private $address;

    function __construct()
    {
        parent::__construct();
    }

    public function regist()
    {
        try{
            $tenniscourt = Tenniscourt::create([
                'court_name' => $this->court_name,
                'zipcode' => $this->zipcode,
                'address' => $this->address
            ]);
        }catch(Exception $e){
            echo $e->getMessage();
            exit;
        }
    }

    function __set($name, $value)
    {
        try{
            if(
                in_array($name, $this->int_properties, true) && is_numeric($value) ||
                in_array($name, $this->str_properties, true) && is_string($value)
            ){
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