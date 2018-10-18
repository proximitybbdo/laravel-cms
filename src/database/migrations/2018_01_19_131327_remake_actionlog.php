<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemakeActionLog extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::drop('actionlog');

        Schema::create('actionlog', function($table)
		{
			$table->increments('id');
			$table->string('user_id')->nullable();
			$table->string('module')->nullable();
			$table->string('action')->nullable();
            $table->string('data')->nullable();
			$table->string('lang')->nullable();
			$table->integer('item_id')->nullable();
            $table->string('ip', 45)->nullable();
			$table->timestamps();

			$table->index('user_id');
			$table->index('module');
			$table->index('lang');
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
