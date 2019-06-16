<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->increments('id');            
            $table->string('type', 50);
            $table->text('content');
            $table->boolean('highlight')->default(0);
            $table->integer('coins');
            $table->integer('user_id')->unsigned();
            $table->integer('post_id')->unsigned();
            $table->softDeletes();	
            $table->timestamps();
        });
        Schema::table(
            'comments', function ($table) {
                $table
                    ->foreign('user_id')
                    ->references('id')
                    ->on('users')
                    ->onDelete('cascade');

                $table
                    ->foreign('post_id')
                    ->references('id')
                    ->on('posts')
                    ->onDelete('cascade');
            }
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('comments');
    }
}
