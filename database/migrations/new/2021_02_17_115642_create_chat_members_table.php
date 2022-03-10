<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChatMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chat_members', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('chat_id')->unsigned();
            $table->foreign('chat_id')->references('id')->on('chats')->onDelete('cascade');
            $table->integer('member_id')->unsigned();
            $table->foreign('member_id')->references('id')->on('users')->onDelete('cascade');
            $table->integer('last_message_id')->default(0);
            $table->integer('last_delete_id')->default(0);
            $table->boolean('is_deleted')->default(FALSE);
            $table->integer('unread_count')->default(0);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('chat_members');
    }
}
