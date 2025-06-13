<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemUserTable extends Migration
{
    public function up()
    {
        Schema::create('item_user', function (Blueprint $table) {
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('item_id')->constrained()->onDelete('cascade');

            $table->primary(['user_id', 'item_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('item_user');
    }
}
