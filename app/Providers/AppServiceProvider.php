<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider {
	/**
	 * Register any application services.
	 *
	 * @return void
	 */
	public function register() {
		$this->app->bind('App\Contracts\SolverContract', function ($app) {
			return new \App\Implementations\SimpleSolver();
		});
	}

	/**
	 * Bootstrap any application services.
	 *
	 * @return void
	 */
	public function boot() {

		// Resource::withoutWrapping();
	}
}
