<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConfigsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    { 
        Schema::create('trips', function (Blueprint $table) {
            $table->increments('id');
            //$table->inerger('trip_id');
            $table->string('staffId')->nullable();
            $table->string('driverId')->nullable();
            $table->string('srcLat')->nullable(); 
            $table->string('srcLong')->nullable();
            $table->string('destLat')->nullable();
            $table->string('destLong')->nullable();
            $table->string('staffName')->nullable();
            $table->string('driverName')->nullable();
            $table->string('parent_code')->nullable();
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
        Schema::dropIfExists('trips');
    }
}
