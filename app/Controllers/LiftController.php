<?php

namespace Carbon\Controllers;

class LiftController extends Controller {
	public function getLifts($request, $response, $args) {
		$query = new Query();

		$mydata = $query->table('lifts')->where('user', '=', $args['id'])->execute();

		return $response->withJson($mydata, 200, JSON_PRETTY_PRINT);
	}

	public function showLiftTable($request, $response, $args) {
		return $this->view->render($response, 'liftTable.php');
	}

	public function postLift($request, $response) {
		$data = $request->getParsedBody();

		var_dump($data);

		$query = new Query();

		if (isset($_POST['real_type'])) {
			$type = $_POST['real_type'];
			$inserted = true;
		} else {
			$type = $_POST['type'];
			$inserted = $query->table('lifttypes')->insert(array('name', 'user'), array($data['type'], $_SESSION['id']))->execute();
		}

		//insert lift
		$result = $query->table('lifts')->insert(array('weight', 'reps', 'type', 'user'), array($data['weight'], $data['reps'], $type, $_SESSION['id']))->execute();

		if ($result && $inserted) {
			echo "New lift added successfully";
			return $response->withStatus(200);
		} else {
			echo "Failed to add lift. Please ensure all variables are properly defined";
			return $response->withStatus(400);
		}

	}
}