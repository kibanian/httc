<?php

declare(strict_types=1);

namespace App\CoreService\User;

use Exception;
use App\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Validator;

class UserRegistService extends UserCoreService
{

    private $str_properties = [
        'name',
        'email',
        'password'
    ];

    private $name;
    private $email;
    private $password;

    function __construct()
    {
        parent::__construct();
    }

    public function regist()
    {
        try{
            $user = User::create([
                //連番でないユーザーIDを作成する
                //記号が付くと不便なので、Laravelのhash関数は使わない
                'user_id' => hash('md5', uniqid((string)mt_rand())),
                'name' => $this->name,
                'email' => $this->email,
                'password' => Hash::make($this->password),
                'facebook_id' => null
            ]);
            return true;
        }catch(Exception $e){
            echo $e->getMessage();
            return false;
        }
    }

    //ユーザーのバリデーション
    public function validate()
    {
        $validator = Validator::make(
            [
                'name' => $this->name,
                'email' => $this->email,
                'password' => $this->password
            ],
            [
                'name' => [
                    'required',
                    'string',
                    'max:255'
                ],
                'email' => [
                    'required',
                    'string',
                    'email',
                    'max:255',
                    'unique:users'
                ],
                'password' => [
                    'required',
                    'string',
                    'min:8',
                    'max:16',
                    'regex:/^(?=.*?[a-z])(?=.*?\d)[a-z\d]{8,100}+$/'
                ]
            ]
        );

        $result = true;
        $messages = [];
        //バリデーションに失敗した場合、メッセージを渡す
        if($validator->fails()){
            foreach($validator->errors()->all() as $error){
                $messages[] = $error;
            }
            $result = false;
        }else{
            $messages[] = 'success';
        }

        Log::debug(sprintf('【%s】LINE:%s validator:%s', __METHOD__, __LINE__, var_export([ $this->name, $this->email, $this->password ], true)));

        Log::debug(sprintf('【%s】LINE:%s validator:%s', __METHOD__, __LINE__, var_export([ $result, $messages ], true)));

        return [ $result, $messages ];
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