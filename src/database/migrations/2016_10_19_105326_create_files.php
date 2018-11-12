<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateFiles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('files', function (Blueprint $table) {
            $table->increments('id');
            $table->string('file', 250);
            $table->string('description', 250)->nullable();
            $table->integer('editor_id');
            $table->string('type');
            $table->tinyInteger('garbage')->default(0);
            $table->timestamps();
            $table->index(array('id', 'type'));
        });
        Schema::create('files_modules', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('file_id');
            $table->string('module_type', 30);
            $table->timestamps();

            $table->index('module_type');
            $table->index(array('file_id', 'module_type'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('files_modules');
        Schema::drop('files');
    }
}
