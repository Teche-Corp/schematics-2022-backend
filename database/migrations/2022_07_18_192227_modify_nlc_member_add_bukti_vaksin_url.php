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
            $table->string("bukti_vaksin_url");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('nlc_member', function (Blueprint $table) {
            $table->dropColumn("bukti_vaksin_url");
        });
    }
};
