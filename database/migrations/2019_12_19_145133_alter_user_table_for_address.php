<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterUserTableForAddress extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->text('zipcode')->nullable();
            $table->text('country')->nullable();
            $table->text('city')->nullable();
            $table->text('state')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->text('zipcode')->nullable();
            $table->text('country')->nullable();
            $table->text('city')->nullable();
            $table->text('state')->nullable();
        });
    }
}
