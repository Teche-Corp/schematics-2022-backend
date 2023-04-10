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
            $table->string('username_lomba')->nullable();
            $table->string('password_lomba')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('npc_team', function (Blueprint $table) {
            $table->dropColumn('username_lomba');
            $table->dropColumn('password_lomba');
        });
    }
};
