<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemsBlock extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items_block', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('item_id')->unsigned();
            $table->string('type', 60);
            $table->string('lang', 5);
            $table->integer('version');
            $table->integer('sort');
            $table->tinyInteger('is_active')->default(1);
            $table->timestamps();

            $table->index(array('item_id', 'version', 'type', 'lang'));
            $table->index(array('item_id', 'version', 'lang'));
            $table->index(array('item_id', 'version'));
            $table->index(array('lang', 'version'));

            $table->foreign('item_id', 'foreign_items_block')->references('id')->on('items');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('items_block', function (Blueprint $table) {
            $table->dropForeign('foreign_items_block');
        });

        Schema::drop('items_block');
    }
}
