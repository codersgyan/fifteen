<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller {
	public function login(Request $request) {

		$request->validate([
			'username' => 'required|string',
			'password' => 'required|string',
		]);

		$http = new \GuzzleHttp\Client;
		try {
			$response = $http->post(config('services.token.login_endpoint'), [
				'form_params' => [
					'grant_type' => 'password',
					'client_id' => config('services.token.client_id'),
					'client_secret' => config('services.token.client_secret'),
					'username' => $request->username,
					'password' => $request->password,
				],
			]);

			return $response->getBody();
		} catch (\GuzzleHttp\Exception\BadResponseException $e) {
			if ($e->getCode() === 400) {
				abort(400, 'Неверный Запрос. Пожалуйста, введите имя пользователя или пароль.');
			} else if ($e->getCode() === 401) {
				abort(401, 'Ваши учетные данные неверны. Пожалуйста, попробуйте еще раз.');
			}
			abort(500, 'Что-то пошло не так на сервере.');
		}
	}

	public function register(Request $request) {

		$request->validate([
			'name' => 'required|string|max:255',
			'email' => 'required|string|email|max:255|unique:users',
			'password' => 'required|string|min:8',
		]);

		$user = new User();
		$user->name = $request->name;
		$user->email = $request->email;
		$user->password = Hash::make($request->password);
		$user->save();

		// Creating a token without scopes...
		$token = $user->createToken($user->email)->accessToken;
		return response()->json(
			[
				'response_code' => 200,
				'access_token' => $token,
				'token_type' => 'Bearer',
			],
			200,
			[],
			JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
	}

	public function logout() {
		auth()->user()->tokens->each(function ($token, $key) {
			$token->delete();
		});
		return response()->json('Успешно вышли из системы', 200);
	}
}
