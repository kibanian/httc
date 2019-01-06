<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FixFeeColumnToTenniscourtFeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tenniscourt_fees', function (Blueprint $table) {
            //
            $table->integer('base_fee_per_hour');
            $table->dropColumn(['weekday_fee_per_hour', 'holiday_fee_per_hour']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tenniscourt_fees', function (Blueprint $table) {
            //
        });
    }
}
