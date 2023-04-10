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
        Schema::create('nlc_team', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->bigInteger('unique_payment_code')->unsigned();
            $table->string('referral_code')->unique()->index();
            $table->string('status');
            $table->string('nama_team');
            $table->string('asal_sekolah');
            $table->string('nama_guru_pendamping');
            $table->string('no_telp_guru_pendamping');
            $table->string('region');
            $table->bigInteger('id_kota')->unsigned();
            $table->bigInteger('biaya');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();

            $table->foreign('id_kota')->references('id')->on('cities');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('nlc_team');
    }
};
