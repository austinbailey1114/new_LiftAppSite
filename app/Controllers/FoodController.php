<?php

namespace Carbon\Controllers;

class FoodController extends Controller {
	public function getFoods($request, $response, $args) {
		$query = new Query();

		$mydata = $query->table('foods')->where('user', '=', $args['id'])->execute();

		return $response->withJson($mydata, 200, JSON_PRETTY_PRINT);
	}
}