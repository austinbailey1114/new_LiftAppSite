<?php

namespace Carbon\Controllers;

class UserController extends Controller {
	public function checkLogin($request, $response) {

		$args = $request->getParsedBody();
		$query = new Query();

		$result = $query->table('users')->select(array('id', 'name'))->where('username', '=', $args['username'])->and_where('password', '=', md5($args['password']))->execute();

		if (count($result > 0)) {
			return $response->withJson($result, 200, JSON_PRETTY_PRINT);
		}
	}

	public function postUser($request, $response) {
		$data = $request->getParsedBody();

		$query = new Query();
		$result = $query->table('users')
						->insert(array('name', 'username', 'password'), array($data['name'], $data['username'], md5($data['password'])))
						->execute();

		if ($result) {
			echo "User created successfully";
			return $response->withStatus(200);
		} else {
			echo "Unable to add user. Please check that variables are properly defined";
			return $reponse->withStatus(400);
		}
	}
}