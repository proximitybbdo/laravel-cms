<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemsBlockLinks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items_block_links', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('item_id')->unsigned();
            $table->integer('block_id')->unsigned();    
            $table->integer('link_id');
            $table->string('link_type',30)->indexed();
            $table->timestamps();

            $table->index(array('item_id','block_id','link_type'));
            $table->index(array('item_id','block_id'));
            $table->index(array('link_type'));

            $table->foreign('block_id','foreign_item_block_links')->references('id')->on('items_block');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('items_block_links', function (Blueprint $table) {
            $table->dropForeign('foreign_item_block_links');
        });

        Schema::drop('items_block_links');
    }
}
