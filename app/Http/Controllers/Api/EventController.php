<?php

namespace App\Http\Controllers\Api;

use App;
use App\Event;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;

class EventController extends Controller
{
    private $er;
    const NULL_NOT_ALLOWED = '#key# Null not allowed';
    

    function __construct()
    {
        $this->er = App::make('EventRegist');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return 'success';
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $inputs = [
            'start_year',
            'start_month',
            'start_date',
            'start_hour',
            'start_minute',
            'end_year',
            'end_month',
            'end_date',
            'end_hour',
            'end_minute',
            'court_id',
            'member_limit',
            'court_number',
            'fee_type',
            'host_id',
            'newcomer_count',
            'status'
        ];
        //
        try{
            foreach($inputs as $key){
                if($request->filled($key)){
                    $this->er->$key = $request->input($key);
                }else{
                    throw new Exception(str_replace('#key#', $key, self::NULL_NOT_ALLOWED));
                }
            }
            $this->er->set_start_datetime();
            $this->er->set_end_datetime();
            $this->er->regist();
        }catch(Exception $e){
            echo $e->getMessage();
            exit;
        }

        return 'success';
        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function show(Event $event)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Event $event)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function destroy(Event $event)
    {
        //
    }
}
