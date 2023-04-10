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
        Schema::create('nst_ticket', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string("name");
            $table->string("email");
            $table->string("no_telp");
            $table->uuid('nst_order_id');
            $table->string('alamat');
            $table->string('jenis_vaksinasi');
            $table->string('bukti_vaksin_url');
            $table->boolean("is_used");
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();

            $table->foreign('nst_order_id')->references('id')->on('nst_order');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('nst_ticket');
    }
};
