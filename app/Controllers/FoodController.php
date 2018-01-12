<?php

namespace Carbon\Controllers;

class FoodController extends Controller {
	public function getFoods($request, $response, $args) {
		$query = new Query();

		$mydata = $query->table('foods')->where('user', '=', $args['id'])->and_where('date', '>', CURDATE())->execute();

		return $response->withJson($mydata, 200, JSON_PRETTY_PRINT);
	}

	public function searchFoods($request, $response, $args) {
		//eventually, cURL the nutrition API

		return $this->view->render($response, 'search.php');

	}
}