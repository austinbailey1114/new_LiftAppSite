<?php

namespace Carbon\Controllers;

class LifttypesController extends Controller {
	public function getLifttypes($request, $response, $args) {
		$query = new Query();

		$mydata = $query->table('lifttypes')->where('user', '=', $args['id'])->execute();

		return $response->withJson($mydata, 200, JSON_PRETTY_PRINT);
	}

	public function postLifttype($request, $response) {
		$data = $request->getParsedBody();
		$query = new Query();

		if (isset($_SESSION['id'])) {
			$id = $_SESSION['id'];
		} else {
			$id = $data['id'];
		}

		// NOTE: This post requires user ID to be passed as POST param in anticipation of api calls from mobile
		$result = $query->table('lifttypes')->insert(array('name', 'user'), array($data['name'], $id))->execute();

		if ($result) {
			echo "New lifttype added successfully";
			return $response->withStatus(200);
		} else {
			echo "Unable to add lifttype. Please make sure variables are correctly defined";
			return $response->withStatus(400);
		}

	}
}