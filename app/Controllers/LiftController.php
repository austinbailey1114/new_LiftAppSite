<?php

namespace Carbon\Controllers;

class LiftController extends Controller {
	//api functions
	public function getLifts($request, $response, $args) {
		$query = new Query();

		$mydata = $query->table('lifts')->where('user', '=', $args['id'])->execute();

		return $response->withJson($mydata, 200, JSON_PRETTY_PRINT);
	}

	public function postLift($request, $response) {
		$data = $request->getParsedBody();

		$query = new Query();

		if (isset($_POST['real_type'])) {
			$type = $_POST['real_type'];
			$inserted = true;
		} else {
			$type = $_POST['type'];
			$inserted = $query->table('lifttypes')->insert(array('name', 'user'), array($data['type'], $data['id']))->execute();
		}

		//insert lift
		$result = $query->table('lifts')->insert(array('weight', 'reps', 'type', 'user'), array($data['weight'], $data['reps'], $type, $data['id']))->execute();

		if ($result && $inserted) {
			echo "New lift added successfully";
			return $response->withStatus(200);
		} else {
			echo "Failed to add lift. Please ensure all variables are properly defined";
			return $response->withStatus(400);
		}
	}

	//app functions
	public function showLiftTable($request, $response, $args) {
		return $this->view->render($response, 'liftTable.php');
	}

	public function addLift($request, $response) {
		$data = $request->getParsedBody();

		$data['id'] = $_SESSION['id'];

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, 'localhost/newLiftAppSite/public/api/lifts/');
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

		$result = curl_exec($ch);

		var_dump($result);

		if ($result) {
			return $response->withHeader('Location', '../');
		}
	}
}