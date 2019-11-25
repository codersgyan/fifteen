<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ImageCollection extends ResourceCollection {

	public function __construct($resource) {
		// Ensure you call the parent constructor
		parent::__construct($resource);
		$this->collection->pop();

	}

	/**
	 * Transform the resource collection into an array.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return array
	 */
	public function toArray($request) {
		return [
			'data' => $this->collection->shuffle(),
		];
	}
}
