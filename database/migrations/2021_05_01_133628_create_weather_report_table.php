<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWeatherReportTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('weather_report', function (Blueprint $table) {
            $table->id();
            $table->string('city_name',200);
            $table->string('description',200);
            $table->string('min_temp',200);
            $table->string('max_temp',200);
            $table->string('humidity',200);
            $table->string('speed',200);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('weather_report');
    }
}
