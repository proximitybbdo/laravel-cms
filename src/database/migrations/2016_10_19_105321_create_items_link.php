<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateItemsLink extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items_link', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('item_id')->unsigned();
            $table->integer('link_id')->unsigned();
            $table->string('link_type', 30)->indexed();
            $table->timestamps();

            $table->unique(array('item_id', 'link_id', 'link_type'));
            $table->foreign('link_id', 'foreign_link_item')->references('id')->on('items');
            $table->foreign('item_id', 'foreign_items_link')->references('id')->on('items');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('items_link', function (Blueprint $table) {
            $table->dropForeign('foreign_items_link');
            $table->dropForeign('foreign_link_item');
        });
        Schema::drop('items_link');
    }
}
