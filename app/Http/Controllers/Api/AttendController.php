<?php

namespace App\Http\Controllers\Api;

use App\Attend;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App;
use Exception;
use Illuminate\Support\Facades\DB;

class AttendController extends Controller
{

    private $ea;
    private $aaf;

    const NULL_NOT_ALLOWED = '#key# Null not allowed';

    function __construct(Request $request)
    {
        if($request->isMethod('post')){
            $this->ea = App::make('EventAttend');
            $this->aaf = App::make('AttenderArrangementFacade');
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
        DB::beginTransaction();
            //
        $inputs = [
            'user_id',
            'event_id',
            'status',
            'card_count'
        ];

        try{
            foreach($inputs as $key){
                if($request->filled($key)){
                    $this->ea->$key = $request->input($key);
                }else{
                    throw new Exception(str_replace('#key#', $key, self::NULL_NOT_ALLOWED).PHP_EOL);
                }
            }

            //既に参加表明済の場合は、例外に飛ばす
            if($this->ea->is_double_entry()){
                throw new Exception("Apply already exists".PHP_EOL);
            }
            $this->ea->attend();
            $this->aaf->event_id = $this->ea->get_event_id();
            $result = $this->aaf->main();
            if(!$result){
                throw new Exception("Something Wrong".PHP_EOL);
            }
            DB::commit();
        }catch(Exception $e){
            echo $e->getMessage().PHP_EOL;
            DB::rollback();
            $result = false;
        }
        
        return $result ? 'success' : 'failed';
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Attend  $attend
     * @return \Illuminate\Http\Response
     */
    public function show(Attend $attend)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Attend  $attend
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Attend $attend)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Attend  $attend
     * @return \Illuminate\Http\Response
     */
    public function destroy(Attend $attend)
    {
        //
    }
}
