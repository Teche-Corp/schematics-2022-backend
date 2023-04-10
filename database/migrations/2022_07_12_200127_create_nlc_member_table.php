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
        Schema::create('nlc_member', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('member_type');
            $table->uuid('team_id')->index();
            $table->uuid('user_id')->index();
            $table->string('nisn');
            $table->string('surat_url');
            $table->string('bukti_twibbon_url');
            $table->string('bukti_poster_url');
            $table->string('no_telp');
            $table->string('no_wa');
            $table->string('id_line');
            $table->string('alamat');
            $table->string('info_sch');
            $table->string('jenis_vaksin');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();

            $table->foreign('team_id')->references('id')->on('nlc_team');
            $table->foreign('user_id')->references('id')->on('user');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('nlc_member');
    }
};
