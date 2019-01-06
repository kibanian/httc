<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Support\Facades\DB;
use App;

class AttendFeeController extends Controller
{
    private $aff;

    function __construct()
    {
        $this->aff = App::make('AttendFeeFacade');
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
        $inputs = [
            'event_id'
        ];
        //
        DB::beginTransaction();
        try{
        //
            foreach($inputs as $key){
                if($request->filled($key)){
                    $this->aff->$key = $request->input($key);
                }else{
                    throw new Exception(sprintf('【%s】LINE:%s %s, Null Not Allowed', __METHOD__, __LINE__, $key).PHP_EOL);
                }
            }
            if(!$this->aff->main()){ throw new Exception("Something Wrong"); }
            DB::commit();
            $result = true;
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
