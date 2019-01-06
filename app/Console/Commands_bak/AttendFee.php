<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Exception;
use Illuminate\Support\Facades\DB;
use App;

class AttendFee extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'attend:update_attend_fee {event_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    private $aff;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->aff = App::make('AttendFeeFacade');
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //

        DB::beginTransaction();
        try{
        //
            $this->aff->event_id = $this->argument('event_id');
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
}
