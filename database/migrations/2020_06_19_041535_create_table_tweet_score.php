<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableTweetScore extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tweetScores', function (Blueprint $table) {
            $table->id();
            $table->string('idTweet', 100);
            $table->double('positif', 15, 8);
            $table->double('negatif', 15, 8);
            $table->double('netral', 15, 8);
            $table->enum('label', ['pos', 'neg', 'neu'])->nullable();
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
        Schema::dropIfExists('tweetScores');
    }
}
