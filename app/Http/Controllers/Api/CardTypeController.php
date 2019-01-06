<?php

namespace App\Http\Controllers\Api;

use App\CardType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Exception;
use App;

class CardTypeController extends Controller
{

    private $ctr;

    function __construct(Request $request)
    {
        if($request->isMethod('post')){
            $this->ctr = App::make('CardTypeRegist');
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
        DB::beginTransaction();
        //
        $inputs = [
            'type'
        ];

        try{
            foreach($inputs as $key){
                if($request->filled($key)){
                    $this->ctr->$key = $request->input($key);
                }else{
                    throw new Exception(sprintf('【%s】LINE:%s %s, Null Not Allowed', __METHOD__, __LINE__, $key).PHP_EOL);
                }
            }
            $result = $this->ctr->regist();
            if(!$result){
                throw new Exception("Something Wrong");
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
     * @param  \App\CardType  $cardType
     * @return \Illuminate\Http\Response
     */
    public function show(CardType $cardType)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\CardType  $cardType
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CardType $cardType)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\CardType  $cardType
     * @return \Illuminate\Http\Response
     */
    public function destroy(CardType $cardType)
    {
        //
    }
}
