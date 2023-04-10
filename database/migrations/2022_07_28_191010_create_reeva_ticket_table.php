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
        Schema::create('reeva_ticket', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('reeva_order_id')->index();
            $table->string('name');
            $table->string('email');
            $table->string('no_telp');
            $table->string('alamat');
            $table->string('nik');
            $table->boolean('is_used');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();

            $table->foreign('reeva_order_id')->references('id')->on('reeva_order');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reeva_ticket');
    }
};
