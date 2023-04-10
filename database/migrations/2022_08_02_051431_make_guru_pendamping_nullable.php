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
        Schema::table('npc_team', function (Blueprint $table) {
            $table->string('nama_guru_pendamping')->nullable()->change();
            $table->string('no_telp_guru_pendamping')->nullable()->change();
        });
        Schema::table('nlc_team', function (Blueprint $table) {
            $table->string('nama_guru_pendamping')->nullable()->change();
            $table->string('no_telp_guru_pendamping')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
