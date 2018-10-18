<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSlugHistory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('slug_history', function($table)
		{
			$table->increments('id');
			$table->integer('item_id')->unsigned();
			$table->string('lang',5)->nullable();
            $table->string('slug',200)->indexed();
			$table->timestamps();

			$table->index(array('lang','slug'),'ix_slughistory_lang_slug');
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('slug_history');
    }
}
