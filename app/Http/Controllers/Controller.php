<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public $l;
    public $api_token;
    const API_TOKEN_HEAD = 'Bearer ';
    
    function __construct(Request $request){
        $this->l = App::make('Login');
        $this->api_token = $request->header('Authorization');
    }

    public function is_login()
    {
        $login_user = Auth::user();
        if(!$login_user){ return false; }
        Log::debug(sprintf('【%s】LINE:%s bool:%s', __METHOD__, __LINE__, $this->api_token == self::API_TOKEN_HEAD.$login_user->api_token));
        return $this->api_token == self::API_TOKEN_HEAD.$login_user->api_token;
    }

}
