<?php

namespace App\Http\Controllers;

use App\Contracts\SolverContract;
use App\Game;

class SolverController extends Controller {

	public function solve(SolverContract $solver) {
		$game = Game::find(2);

		$images = $game->images->map(function ($item, $key) {
			return $item->image;
		})->all();

		# Original Array
		$originalArray = $images;
		array_pop($originalArray);
		$originalArray[] = null;

		$shuffledArray = $images;
		shuffle($shuffledArray);
		array_pop($shuffledArray);
		$shuffledArray[] = null;
		# Shuffled Array

		$solver->solve($originalArray, $shuffledArray, $game->level);
	}

}
