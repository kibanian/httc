<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTenniscourtFeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tenniscourt_fees', function (Blueprint $table) {
            $table->integer('court_id');
            $table->integer('date');
            $table->integer('weekday_fee_per_hour');
            $table->integer('holiday_fee_per_hour');
            $table->integer('lighting_fee_per_hour');
            $table->integer('other_fee');
            $table->timestamps();
            $table->primary(['court_id', 'date']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tenniscourt_fees');
    }
}
