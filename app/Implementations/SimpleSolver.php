<?php
namespace App\Implementations;

use App\Contracts\SolverContract;

class SimpleSolver implements SolverContract {
	protected $level;
	protected $arrayCount;
	protected $shuffledArray;
	protected $currentPosition;
	protected $originalArray;
	protected $moves = [];
	protected $movedFrom;

	public function solve($originalArray, $shuffledArray, $level) {

		$this->originalArray = $originalArray;
		$this->shuffledArray = $shuffledArray;
		$this->level = $level;

		$this->arrayCount = count($this->shuffledArray);

		$movesMap = [
			'up' => $this->possibleUps(),
			'down' => $this->possibleDowns(),
			'left' => $this->possibleLefts(),
			'right' => $this->possibleRights(),
		];

		$count = 0;
		$this->currentPosition = (count($this->shuffledArray) - 1);

		while ($this->shuffledArray !== $this->originalArray) {

			$posibleMoves = [];
			foreach ($movesMap as $key => $map) {
				if (in_array($this->currentPosition, $map)) {
					$posibleMoves[] = $key;
				}
			}

			# remove previous move
			$posibleMoves = array_filter($posibleMoves, function ($move) {
				return $move !== $this->movedFrom;
			});

			$report = [];

			foreach ($posibleMoves as $key => $method) {
				$report[$method] = $this->$method();
			}

			$mismatch = [];
			foreach ($report as $num => $mis) {
				foreach ($mis as $key => $value) {
					if ($this->originalArray[$key] !== $value) {
						$mismatch[$num][] = $value;
					}
				}
			}

			$recommendedMove = array_keys($mismatch, min($mismatch))[0];

			switch ($recommendedMove) {
			case 'up':
				$this->movedFrom = 'down';
				break;
			case 'down':
				$this->movedFrom = 'up';
				break;
			case 'left':
				$this->movedFrom = 'right';
				break;
			case 'right':
				$this->movedFrom = 'left';
				break;
			}

			# move to the recommended side

			$this->$recommendedMove(true);
			$this->moves[] = $recommendedMove;
			$count++;
			if ($count > 2000) {
				break;
			}
		}

		// echo "Done";
		dump($this->shuffledArray);
		dd($this->moves);
	}

	public function possibleUps() {
		$values = [];
		foreach (range($this->level + 1, $this->arrayCount - 1) as $value) {
			$values[] = $value;
		}
		return $values;
	}

	public function possibleDowns() {
		$values = [];
		foreach (range(0, ($this->arrayCount - 1) - ($this->level + 1)) as $value) {
			$values[] = $value;
		}
		return $values;
	}

	public function possibleLefts() {
		$values = [];
		foreach (range(1, ($this->arrayCount - 1)) as $value) {
			if ($value % ($this->level + 1) !== 0) {
				$values[] = $value;
			}
		}
		return $values;
	}
	public function possibleRights() {
		$values = [];
		foreach (range(0, ($this->arrayCount - 1)) as $value) {
			if (($value + 1) % ($this->level + 1) !== 0) {
				$values[] = $value;
			}
		}
		return $values;
	}

	public function up($move = false) {
		if ($move) {
			# Swaping ( Moving blank space up )
			$temp = $this->shuffledArray[$this->currentPosition - ($this->level + 1)];
			$this->shuffledArray[$this->currentPosition - ($this->level + 1)] =
			$this->shuffledArray[$this->currentPosition];
			$this->shuffledArray[$this->currentPosition] = $temp;
			$this->currentPosition = ($this->currentPosition - ($this->level + 1));
			return true;
		}
		$localArray = $this->shuffledArray;
		# Swaping ( Moving blank space up )
		$temp = $localArray[$this->currentPosition - ($this->level + 1)];
		$localArray[$this->currentPosition - ($this->level + 1)] = $localArray[$this->currentPosition];
		$localArray[$this->currentPosition] = $temp;
		return $localArray;
	}

	public function down($move = false) {
		if ($move) {
			# Swaping ( Moving blank space down )
			$temp = $this->shuffledArray[$this->currentPosition + ($this->level + 1)];
			$this->shuffledArray[$this->currentPosition + ($this->level + 1)] = $this->shuffledArray[$this->currentPosition];
			$this->shuffledArray[$this->currentPosition] = $temp;
			$this->currentPosition = $this->currentPosition + ($this->level + 1);
			return true;
		}
		$localArray = $this->shuffledArray;
		# Swaping ( Moving blank space down )
		$temp = $localArray[$this->currentPosition + ($this->level + 1)];
		$localArray[$this->currentPosition + ($this->level + 1)] = $localArray[$this->currentPosition];
		$localArray[$this->currentPosition] = $temp;
		return $localArray;
	}

	public function left($move = false) {
		if ($move) {
			# Swaping ( Moving blank space left )
			$temp = $this->shuffledArray[$this->currentPosition - 1];
			$this->shuffledArray[$this->currentPosition - 1] = $this->shuffledArray[$this->currentPosition];
			$this->shuffledArray[$this->currentPosition] = $temp;
			$this->currentPosition = ($this->currentPosition - 1);
			return true;
		}
		$localArray = $this->shuffledArray;
		# Swaping ( Moving blank space left )
		$temp = $localArray[$this->currentPosition - 1];
		$localArray[$this->currentPosition - 1] = $localArray[$this->currentPosition];
		$localArray[$this->currentPosition] = $temp;
		return $localArray;
	}

	public function right($move = false) {
		if ($move) {
			# Swaping ( Moving blank space right )
			$temp = $this->shuffledArray[$this->currentPosition + 1];
			$this->shuffledArray[$this->currentPosition + 1] = $this->shuffledArray[$this->currentPosition];
			$this->shuffledArray[$this->currentPosition] = $temp;
			$this->currentPosition = ($this->currentPosition + 1);
			return true;
		}
		$localArray = $this->shuffledArray;
		# Swaping ( Moving blank space right )
		$temp = $localArray[$this->currentPosition + 1];
		$localArray[$this->currentPosition + 1] = $localArray[$this->currentPosition];
		$localArray[$this->currentPosition] = $temp;
		return $localArray;
	}
}