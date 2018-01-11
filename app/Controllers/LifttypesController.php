<?php

namespace Carbon\Controllers;

class LifttypesController extends Controller {
	public function getLifttypes($request, $response, $args) {
		$query = new Query();

		$mydata = $query->table('lifttypes')->where('user', '=', $args['id'])->execute();

		return $response->withJson($mydata, 200, JSON_PRETTY_PRINT);
	}
}