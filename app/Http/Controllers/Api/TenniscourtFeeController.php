<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App;

class TenniscourtFeeController extends Controller
{

    private $tfr;
    private $tfc;

    function __construct(Request $request)
    {
        if($request->isMethod('post')){
            $this->tfc = App::make('TenniscourtFeeCalc');
            $this->tfr = App::make('TenniscourtFeeRegist');
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
            'court_id',
            'base_fee_per_hour',
            'lighting_fee_per_hour',
            'other_fee',
            'year'
        ];

        try{
            foreach($inputs as $key){
                if($request->filled($key)){
                    $this->tfc->$key = $request->input($key);
                }else{
                    throw new Exception(str_replace('#key#', $key, self::NULL_NOT_ALLOWED));
                }
            }
            $this->tfc->calc();
            $this->tfr->court_fees = $this->tfc->get_court_fees();
            $result = $this->tfr->regist();
        }catch(Exception $e){
            echo $e->getMessage();
            exit;
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
