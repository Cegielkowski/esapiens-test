<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('notification_from')->unsigned();
            $table->integer('notification_to')->unsigned();
            $table->integer('comment_id')->unsigned();
            $table->integer('post_id')->unsigned();
            $table->boolean('viewed')->nullable();
            $table->softDeletes();	
            $table->timestamps();
        });
        Schema::table(
            'notifications', function ($table) {
                $table
                    ->foreign('notification_from')
                    ->references('id')
                    ->on('users')
                    ->onDelete('cascade');
                $table
                    ->foreign('notification_to')
                    ->references('id')
                    ->on('users')
                    ->onDelete('cascade');
                $table
                    ->foreign('comment_id')
                    ->references('id')
                    ->on('comments')
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
        Schema::dropIfExists('notifications');
    }
}
