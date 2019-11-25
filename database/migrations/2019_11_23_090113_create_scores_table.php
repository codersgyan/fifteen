<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateScoresTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('scores', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->unsignedBigInteger('game_id');
			$table->unsignedBigInteger('player_id');
			$table->unsignedBigInteger('moves');
			$table->time('time');
			$table->boolean('has_solved')->default(0);
			$table->timestamps();
		});

		Schema::table('scores', function (Blueprint $table) {
			$table->foreign('game_id')->references('id')->on('games')->onDelete('cascade');
			$table->foreign('player_id')->references('id')->on('users')->onDelete('cascade');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::dropIfExists('scores');
	}
}
