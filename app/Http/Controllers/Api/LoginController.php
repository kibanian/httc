<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Exception;
use App;

class LoginController extends Controller
{
    const NULL_NOT_ALLOWED = '#key# Null not allowed';

    function __construct(Request $request)
    {
        parent::__construct($request);
    }
    //
    public function login(Request $request)
    {
        $inputs = [
            'email',
            'password'
        ];
        
        $messages = [];

        //
        try{
            foreach($inputs as $key){
                if($request->filled($key)){
                    $this->l->$key = $request->input($key);
                }else{
                    throw new Exception(sprintf('【%s】LINE:%s %s, Null Not Allowed', __METHOD__, __LINE__, $key).PHP_EOL);
                }
            }
            
            list($token, $message) = $this->l->authenticate();
            if(!$token){ throw new Exception($message); }

            return [
                'result' => 'success',
                'access_token' => $token,
                'token_type' => 'bearer',
                'message' => null
            ];
            
        }catch(Exception $e){
            Log::debug(sprintf('【%s】LINE:%s %s', __METHOD__, __LINE__, $e->getMessage()));
            $messages[] = $e->getMessage();
            return [
                'result' => 'failed',
                'access_token' => null,
                'token_type' => 'bearer',
                'message' => $messages
            ];
        }
    }

    public function me()
    {
        return Auth::user();
    }

    public function logout()
    {
        try{
            $this->l->unauthenticate();
            return [
                'result' => 'success',
                'message' => null
            ];
        }catch(Exception $e){
            Log::debug(sprintf('【%s】LINE:%s %s', __METHOD__, __LINE__, $e->getMessage()));
            return [
                'result' => 'success',
                'message' => null
            ];
        }
    }
}
