<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItems extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items', function(Blueprint $table) {
            $table->increments('id');
            $table->string('module_type',30);
            $table->integer('category_id')->unsigned()->nullable();
            $table->string('description',250)->nullable();
            $table->integer('sort');
            $table->datetime('start_date')->nullable();
            $table->datetime('end_date')->nullable();
            $table->string('type')->nullable();
            $table->tinyInteger('status')->default(0);
            $table->string('version')->nullable();
            $table->integer('editor_id');           
            $table->timestamps();

            $table->index('module_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('items');
    }
}
