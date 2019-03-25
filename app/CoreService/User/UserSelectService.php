<?php

declare(strict_types=1);

namespace App\CoreService\User;

use Exception;
use App\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UserSelectService extends UserCoreService
{

    private $users = [];

    private $str_properties = [
        'user_id'
    ];

    private $user_id;

    function __construct()
    {
        parent::__construct();
    }

    public function select_all()
    {
        DB::enableQueryLog();
        $this->users = [];
        try{
            //イベントIDの指定がなければ全件取得
            foreach(User::all() as $user){
                $this->users[] = [
                    'id' => $user->id,
                    'user_id' => $user->user_id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'cards' => $user->card
                ];
            }

            //Log::debug(sprintf('【%s】LINE:%s users:%s', __METHOD__, __LINE__, var_export(DB::getQueryLog(), true)));

        }catch(Exception $e){
            Log::debug($e);
            echo $e;
        }

        return $this->users;
    }

    function __set($name, $value)
    {
        try{
            if(
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