<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemsBlockContent extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items_block_content', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('item_id')->unsigned();
            $table->integer('block_id')->unsigned();            
            $table->string('type',60);
            $table->text('content');
            $table->timestamps();

            $table->index(array('item_id','block_id','type'));
            $table->index(array('item_id','block_id'));
            $table->index(array('item_id','type'));

            $table->foreign('block_id','foreign_items_block_content')->references('id')->on('items_block');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('items_block_content', function (Blueprint $table) {
            $table->dropForeign('foreign_items_block_content');
        });

        Schema::drop('items_block_content');
    }
}
