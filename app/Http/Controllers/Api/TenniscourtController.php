<?php

namespace App\Http\Controllers\Api;

use App\Tenniscourt;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App;
use Exception;

class TenniscourtController extends Controller
{
    private $tr;

    const NULL_NOT_ALLOWED = '#key# Null not allowed';

    function __construct(Request $request)
    {
        if($request->isMethod('post')){
            $this->tr = App::make('TenniscourtRegist');
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
            'court_name',
            'zipcode',
            'address'
        ];

        try{
            foreach($inputs as $key){
                if($request->filled($key)){
                    $this->tr->$key = $request->input($key);
                }else{
                    throw new Exception(str_replace('#key#', $key, self::NULL_NOT_ALLOWED));
                }
            }
            $this->tr->regist();
            
        }catch(Exception $e){
            echo $e->getMessage();
            exit;
        }

        return 'success';
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Tenniscourt  $tenniscourt
     * @return \Illuminate\Http\Response
     */
    public function show(Tenniscourt $tenniscourt)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Tenniscourt  $tenniscourt
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Tenniscourt $tenniscourt)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Tenniscourt  $tenniscourt
     * @return \Illuminate\Http\Response
     */
    public function destroy(Tenniscourt $tenniscourt)
    {
        //
    }
}
