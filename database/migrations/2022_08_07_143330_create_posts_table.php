<?php

use Faker\Factory;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $views = (Factory::create())->numberBetween(155, 255);
            $likes = (Factory::create())->numberBetween(15, 25);
            $table->id();
            $table->string('title', 250);
            $table->longText('content');
            $table->longText('banner');
            $table->bigInteger('likes')->default($likes);
            $table->bigInteger('views')->default($views);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('posts');
    }
};
