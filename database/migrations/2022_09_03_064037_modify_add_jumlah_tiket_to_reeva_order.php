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
        Schema::table('reeva_order', function (Blueprint $table) {
            $table->integer('jumlah_tiket')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('reeva_order', function (Blueprint $table) {
            $table->dropColumn('jumlah_tiket');
        });
    }
};
