<?php

namespace Carbon\Controllers;

class LiftController extends Controller {
	public function getLifts($request, $response, $args) {
		$query = new Query();

		$mydata = $query->table('lifts')->where('user', '=', $args['id'])->execute();

		return $response->withJson($mydata, 200, JSON_PRETTY_PRINT);
	}
}