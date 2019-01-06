<?php

namespace App\Http\Controllers\Api;

use App\Cancel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App;
use Illuminate\Support\Facades\DB;
use Exception;

class CancelController extends Controller
{
    
    private $cf;

    function __construct(Request $request)
    {
        if($request->isMethod('post')){
            $this->cf = App::make('CancelFacade');
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
            'event_id',
            'user_id'
        ];

        try{
            foreach($inputs as $key){
                if($request->filled($key)){
                    $this->cf->$key = $request->input($key);
                }else{
                    throw new Exception(sprintf('【%s】LINE:%s %s, Null Not Allowed', __METHOD__, __LINE__, $key).PHP_EOL);
                }
            }
            $result = $this->cf->main();
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
     * @param  \App\Cancel  $cancel
     * @return \Illuminate\Http\Response
     */
    public function show(Cancel $cancel)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Cancel  $cancel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Cancel $cancel)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Cancel  $cancel
     * @return \Illuminate\Http\Response
     */
    public function destroy(Cancel $cancel)
    {
        //
    }
}
