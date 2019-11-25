<?php
namespace App\Http\Controllers;

use App\Game;
use App\Http\Resources\Game as GameResource;
use App\Http\Resources\GameCollection;
use App\Services\ImageSlicer;
use Illuminate\Http\Request;

class GameController extends Controller {

	/**
	 * @return Resource
	 */
	public function index() {
		$games = Game::paginate(15);
		return new GameCollection($games);
	}

	/**
	 * @return Resource
	 * @param App\Game $game
	 */
	public function show(Game $game) {
		return new GameResource($game);
	}

	/**
	 * @return Resource
	 * @param Illuminate\Http\Request $request
	 * @param App\Services\ImageSlicer $slicer
	 */
	public function store(Request $request, ImageSlicer $slicer) {

		$this->validate($request, [
			'image' => 'required | image',
			'name' => 'required',
		]);

		$game = new Game();
		$game->name = $request->name;
		$game->creator_id = auth()->id();
		$game->level = $request->level;
		$game->original_image = $request->file('image')->store('originals');
		$game->save();

		# Calling the Slicer service to slice the image in given number of parts
		$images = $slicer
			->take($request->file('image'))
			->addLevel($request->level)
			->slice();

		# Formating the data to required format to save in the database
		$formattedData = array_map(function ($element) use ($game) {
			return [
				'game_id' => $game->id,
				'image' => $element,
			];
		}, $images);
		$game->images()->createMany($formattedData);

		return new GameResource($game);
	}

}
