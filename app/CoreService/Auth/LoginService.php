<?php

declare(strict_types=1);

namespace App\CoreService\Auth;

use Exception;
use App\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class LoginService extends AuthCoreService
{
    private $str_properties = [
        'email',
        'password'
    ];

    private $email;
    private $password;

    function __construct()
    {
        parent::__construct();
    }

    public function authenticate()
    {
        $is_auth = Auth::attempt([
            'email' => $this->email,
            'password' => $this->password
        ]);
        
        //認証失敗
        if(!$is_auth){
            return [ null, '401 Unauthorized' ];
        }

        //認証したユーザー
        $user = Auth::user();
        //ログインごとに異なるトークンを保存する
        $token = Str::random(60);
        //$user->update(['api_token' => $token]);
        $user->api_token = $token;
        $user->save();

        return [ $token, null ];
    }

    //ログアウト処理
    public function unauthenticate()
    {
        $user = Auth::user();
        $user->api_token = null;
        $user->save();
        //セッション削除はweb側のguardで行う
        Auth::guard('web')->logout();
    }

    public function is_authenticated($api_token)
    {
        $login_user = Auth::user();
        return $login_user->api_token == $api_token;
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