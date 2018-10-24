<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateItemsContent extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items_content', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('item_id')->unsigned();
            $table->integer('version');
            $table->string('lang', 5);
            $table->string('type', 60);
            $table->text('content');
            $table->timestamps();

            $table->index(array('item_id', 'lang', 'type'));
            $table->index(array('lang', 'type'));
            $table->index(array('lang'));
        });

        //DB::statement('ALTER TABLE items_content ADD FULLTEXT search(content)');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('items_content');
    }
}
