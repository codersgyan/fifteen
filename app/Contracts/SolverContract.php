<?php
namespace App\Contracts;

interface SolverContract {
	public function solve($originalArray, $shuffledArray, $level);
}