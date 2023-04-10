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
        Schema::table('nlc_team', function (Blueprint $table) {
            $table->string('kode_voucher', 32)->nullable();
        });
        Schema::table('npc_team', function (Blueprint $table) {
            $table->string('kode_voucher', 32)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('nlc_team', function (Blueprint $table) {
            $table->dropColumn('kode_voucher');
        });
        Schema::table('npc_team', function (Blueprint $table) {
            $table->dropColumn('kode_voucher');
        });
    }
};
