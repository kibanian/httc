<?php

namespace App\Http\Controllers\Api;

use App\Card;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Exception;
use App;

class CardController extends Controller
{

    private $cr;

    function __construct(Request $request)
    {
        if($request->isMethod('post')){
            $this->cr = App::make('CardRegist');
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
            'user_id',
            'card_type',
            'made_year',
            'made_month',
            'made_date'
        ];

        try{
            foreach($inputs as $key){
                if($request->filled($key)){
                    $this->cr->$key = $request->input($key);
                }else{
                    throw new Exception(sprintf('【%s】LINE:%s %s, Null Not Allowed', __METHOD__, __LINE__, $key).PHP_EOL);
                }
            }
            //if($this->cr->is_already_registed()){ throw new Exception("Already Registed."); }
            $result = $this->cr->regist();
            if(!$result){ throw new Exception("Something Wrong"); }
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
     * @param  \App\Card  $card
     * @return \Illuminate\Http\Response
     */
    public function show(Card $card)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Card  $card
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Card $card)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Card  $card
     * @return \Illuminate\Http\Response
     */
    public function destroy(Card $card)
    {
        //
    }
}
