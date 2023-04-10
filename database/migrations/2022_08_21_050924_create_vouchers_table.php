<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vouchers', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('kode', 32)->unique();
            $table->bigInteger('potongan')->unsigned();
            $table->bigInteger('kuota')->unsigned();
            $table->integer('region')->unsigned()->nullable();
            $table->dateTime('start_time');
            $table->dateTime('end_time');
            $table->string('tipe', 16);
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
        Schema::dropIfExists('vouchers');
    }
};
