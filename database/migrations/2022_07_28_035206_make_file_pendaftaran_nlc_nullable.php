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
        Schema::table('nlc_member', function (Blueprint $table) {
            $table->string('bukti_twibbon_url')->nullable()->change();
            $table->string('bukti_poster_url')->nullable()->change();
            $table->string("bukti_vaksin_url")->nullable()->change();
            $table->string('jenis_vaksin')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    }
};
