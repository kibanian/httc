<?php

namespace App\Http\Controllers\Api;

use App;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Exception;

class UserController extends Controller
{
    private $ur;
    private $us;
    const NULL_NOT_ALLOWED = '#key# Null not allowed';

    function __construct(Request $request)
    {
        if($request->ismethod('get')){
            $this->us = App::make('UserSelect');
        }
        if($request->isMethod('post')){
            $this->ur = App::make('UserRegist');
        }
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $users = $this->us->select_all();
        return [
            'result' => $users ? 'success' : 'failed',
            'users' => $users
        ];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $inputs = [
            'name',
            'email',
            'password'
        ];

        $messages = [];
        //
        try{
            foreach($inputs as $key){
                if($request->filled($key)){
                    $this->ur->$key = $request->input($key);
                }else{
                    throw new Exception(str_replace('#key#', $key, self::NULL_NOT_ALLOWED));
                }
            }
            list($result, $messages) = $this->ur->validate();
            if($result){ $result = $this->ur->regist(); }
        }catch(Exception $e){
            echo $e->getMessage();
            $result = false;
            if($messages == []){ $messages[] = 'Something wrong.'; }
        }

        return $result ? [ 'success', [] ] : [ 'failed', $messages ];
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        //
    }
}
