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
        Schema::create('midtrans_exception_log', function (Blueprint $table) {
            $table->id();
            $table->string('pembayaran_id')->index();
            $table->string('midtrans_transaction_id')->nullable();
            $table->string('context');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('midtrans_exception_log');
    }
};
