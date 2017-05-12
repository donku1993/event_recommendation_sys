<?php

namespace App\Models;
/**
* CosineSimilarity measures a cosine similarity between two vectors
*
* reference from: https://github.com/aoiaoi/CosineSimilarity
*/
class CosineSimilarity {

	public static function similarity(array $vec1, array $vec2) {
		return self::_dotProduct($vec1, $vec2) / (self::_absVector($vec1) * self::_absVector($vec2));
	}

	protected static function _dotProduct(array $vec1, array $vec2) {
		$result = 0;

		foreach (array_keys($vec1) as $key1) {
			foreach (array_keys($vec2) as $key2) {
				if ($key1 === $key2) $result += $vec1[$key1] * $vec2[$key2];
			}
		}

		return $result;
	}

	protected static function _absVector(array $vec) {
		$result = 0;

		foreach (array_values($vec) as $value) {
			$result += $value * $value;
		}

		return sqrt($result);
	}
}