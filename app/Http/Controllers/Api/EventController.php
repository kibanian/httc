<?php

namespace App\Http\Controllers\Api;

use App;
use App\Event;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Exception;

class EventController extends Controller
{
    private $er;
    private $es;
    const NULL_NOT_ALLOWED = '#key# Null not allowed.';
    const IS_NOT_AUTHENTICATED = 'Not Authenticated.';
    

    function __construct(Request $request)
    {
        parent::__construct($request);
        if($request->isMethod('get')){
            $this->es = App::make('EventSelect');

        }
        if($request->isMethod('post')){
            $this->er = App::make('EventRegist');
        }
        
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(!$this->is_login()){
            return [
                'result' => 'failed',
                'events' => [],
                'is_login' => false    
            ];
        }
        //
        $events = $this->es->main();
        return [
            'result' => $events ? 'success' : 'failed',
            'events' => $events,
            'is_login' => true
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
            if(!$this->is_login()){ throw new Exception(self::NOT_AUTHENTICATED); }
            foreach($inputs as $key){
                if($request->filled($key)){
                    $this->er->$key = (int)$request->input($key);
                }else{
                    throw new Exception(str_replace('#key#', $key, self::NULL_NOT_ALLOWED));
                }
            }
            $this->er->set_start_datetime();
            $this->er->set_end_datetime();
            $result = $this->er->regist();
        }catch(Exception $e){
            echo $e->getMessage();
            $result = false;
        }

        return $result ? 'success' : 'failed';
        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function show(string $id)
    {

        $event = [];
        //
        try{
            if(!$this->is_login()){ throw new Exception(self::IS_NOT_AUTHENTICATED); }

            $login_user_id = Auth::user()->user_id;

            $this->es->event_id = (int)$id;
            $event = $this->es->select_one();
            
        }catch(Exception $e){
            echo $e->getMessage();
            $login_user_id = null;
            $event = [];
        }

        return [
            'result' => $event ? 'success' : 'failed',
            'event' => $event,
            'login_user_id' => $login_user_id,
            'is_login' => !is_null($login_user_id)
        ];
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
