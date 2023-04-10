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
        Schema::create('nlc_feedback', function (Blueprint $table) {
            $table->uuid('id');
            $table->uuid('nlc_team_id');
            $table->string('nama_ketua');
            $table->string('nama_anggota_1')->nullable();
            $table->string('nama_anggota_2')->nullable();
            $table->string('nama_sekolah');
            $table->integer('tingkat_kepuasan');
            $table->integer('babak_game');
            $table->integer('babak_soal');
            $table->boolean('terdapat_kendala');
            $table->string('kesan');
            $table->string('kritik_saran');
            $table->timestamp('created_at')->useCurrent();

            $table->foreign('nlc_team_id')->references('id')->on('nlc_team');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('nlc_feedback');
    }
};
