<?php

namespace App\Http\Controllers;

use App\Game;
use App\Score;
use Illuminate\Http\Request;

class ScoreController extends Controller {

	/**
	 * @param Illuminate\Http\Request $request
	 * @return Json
	 */
	public function store(Request $request) {
		# Get the Game from database which player has played
		$game = Game::findOrFail($request->game_id);
		$game->images->pop();

		# Format the images sequences in the format so we can
		# compare with the data array getting from request
		$images = $game->images->map(function ($item, $key) {
			return $item->image;
		});

		# validate the request data ( Could be create a seperate Request class )
		$this->validate($request, [
			'game_id' => 'required',
			'moves' => 'required',
			'time' => 'required | date_format:H:i:s',
		]);

		# Store the score in the database
		$score = new Score();
		$score->player_id = auth()->id();
		$score->game_id = $request->game_id;
		$score->moves = $request->moves;
		$score->time = $request->time;

		# Comparing Array from request and one from database ( Original sequence )
		$has_solved = ($images->all() === $request->data) ? true : false;

		$score->has_solved = $has_solved;
		$score->save();

		return response()->json([
			'has_solved' => $has_solved,
		]);

	}
}
