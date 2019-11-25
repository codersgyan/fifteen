<?php
namespace App\Services;

use Illuminate\Support\Facades\Storage;

class ImageSlicer {

	protected $sliced_images = [];
	protected $empty_images = [];
	protected $image_file;
	protected $layer_size;
	protected $image_width;
	protected $image_height;

	/**
	 * Handle an incoming Image. Get image Info
	 *
	 * @param  File  $image
	 * @return App\Services\ImageSlicer $this
	 */
	public function take($image) {
		$this->image_file = imagecreatefromjpeg($image);
		$ImageInfo = getimagesize($image);

		$this->image_width = $ImageInfo[0];
		$this->image_height = $ImageInfo[1];

		return $this;
	}

	/**
	 * Add a level to images ( How many slices to be cut)
	 *
	 * @param  Int $level
	 * @return App\Services\ImageSlicer $this
	 */
	public function addLevel($level = 1) {
		$this->layer_size = config('game.levels_layers_map')[$level];
		return $this;
	}

	/**
	 * Slice and save the image on the server
	 * @return Array $slicedImages
	 */
	public function slice() {
		$piece_width = $this->image_width / $this->layer_size;
		$piece_height = $this->image_height / $this->layer_size;

		$src_image_start_x = 0;
		$src_image_start_y = 0;

		foreach (range(1, pow($this->layer_size, 2)) as $iteration) {

			$this->empty_images[$iteration] = imagecreatetruecolor($piece_width, $piece_height);

			imagecopy(
				$this->empty_images[$iteration],
				$this->image_file,
				0,
				0,
				$src_image_start_x,
				$src_image_start_y,
				$piece_width,
				$piece_height
			);

			ob_start();
			imagejpeg($this->empty_images[$iteration]);
			$jpeg_image = ob_get_clean();

			$path = $iteration . '_' . time() . '_' . auth()->id() . '.jpg';
			Storage::disk('public')->put($path, $jpeg_image);

			$this->sliced_images[] = $path;

			if ($iteration % $this->layer_size === 0) {
				$src_image_start_x = 0;
				$src_image_start_y += $piece_height;
			} else {
				$src_image_start_x += $piece_width;
			}

		}

		return $this->sliced_images;
	}
}