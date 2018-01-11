<?php

namespace Carbon\Controllers;

class BodyweightController extends Controller {
	public function getBodyweights($request, $response, $args) {
		$query = new Query();

		$mydata = $query->table('bodyweights')->where('user', '=', $args['id'])->execute();

		return $response->withJson($mydata, 200, JSON_PRETTY_PRINT);
	}
}