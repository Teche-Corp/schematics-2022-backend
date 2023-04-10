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
        Schema::create('pembayaran', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('status');
            $table->string('tipe_pembayaran');
            $table->uuid('subject_id');
            $table->string('tipe_bank');
            $table->string('nama_rekening');
            $table->string('no_rekening');
            $table->string('kode_transfer');
            $table->string('bukti_bayar_url');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();

            $table->unique(['subject_id', 'tipe_pembayaran']);
            $table->unique(['tipe_bank', 'kode_transfer']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pembayaran');
    }
};
