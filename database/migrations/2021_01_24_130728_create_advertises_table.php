<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdvertisesTable extends Migration
{
    public function up()
    {
        Schema::create('advertises', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id')->nullable();
            $table->integer('admin_id')->nullable();
            $table->text('title');
            $table->enum('status',['منزل','مغازه','زمین']);
            $table->enum('type',['فروش','رهن و اجاره']);
            $table->string('neighborhood');
            $table->string('street');
            $table->integer('area');
            $table->boolean('is_in_lane');
            $table->float('lane_width')->nullable();
            $table->float('length_house')->nullable();
            $table->float('roof_number')->nullable();
            $table->string('lifetime_state')->nullable();
            $table->string('skeleton_state')->nullable();
            $table->integer('price')->nullable();
            $table->text('alphabet_price')->nullable();
            $table->integer('rent')->nullable();
            $table->integer('meed')->nullable();
            $table->text('address')->nullable();
            $table->text('note')->nullable();
            $table->text('description')->nullable();
            $table->boolean('confirmed');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('advertises');
    }
}
