<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateActionlog extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('actionlog', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->biginteger('lead_id')->unsigned()->indexed();
            $table->string('action')->indexed();
            $table->string('data')->nullable();
            $table->integer('category_id')->unsigned()->nullable();
            $table->integer('score')->nullable();
            $table->string('info')->nullable();
            $table->string('ip', 45)->nullable();
            $table->string('referer')->nullable();
            $table->string('user_agent')->nullable();
            $table->string('url', 500)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('actionlog');
    }
}
