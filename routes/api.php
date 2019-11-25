<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
 */

Route::middleware('auth:api')->get('/user', function (Request $request) {
	return $request->user();
});

Route::middleware(['auth:api'])->group(function () {
	Route::post('/logout', 'Auth\AuthController@logout');

	# Generate a single game and return generated game
	Route::post('games/generate', 'GameController@store');

	# Get all paginated game
	Route::get('games/all', 'GameController@index');

	# Show a single game by Id
	Route::get('games/show/{game}', 'GameController@show');

	# Send score to the server
	Route::get('game/scores/store', 'ScoreController@store');
});

Route::get('solve', 'SolverController@solve');

Route::post('/login', 'Auth\AuthController@login');
Route::post('/register', 'Auth\AuthController@register');
