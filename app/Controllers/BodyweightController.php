<?php

namespace Carbon\Controllers;

class BodyweightController extends Controller {
	public function getBodyweights($request, $response, $args) {
		$query = new Query();

		$mydata = $query->table('bodyweights')->where('user', '=', $args['id'])->execute();

		return $response->withJson($mydata, 200, JSON_PRETTY_PRINT);
	}

	public function showBodyweightTable($request, $response) {
		return $this->view->render($response, 'bodyweightTable.php');
	}

	public function postBodyweight($request, $response) {
		$data = $request->getParsedBody();

		$query = new Query();

		if (isset($_SESSION['id'])) {
			$id = $_SESSION['id'];
		} else {
			$id = $data['id'];
		}

		$result = $query->table('bodyweights')->insert(array('weight', 'user'), array($data['updateWeight'], $id))->execute();

		if ($result) {
			echo "New bodyweight added successfully";
			return $response->withStatus(200);
		} else {
			echo "Unable to add bodyweight. Please ensure all variables are added correctly";
			return $response->withStatus(400);
		}
	}
}