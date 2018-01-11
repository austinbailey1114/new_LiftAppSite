<?php

namespace Carbon\Controllers;

class UserController extends Controller {
	public function checkLogin($request, $response) {

		$args = $request->getParsedBody();
		$query = new Query();

		$result = $query->table('users')->select(array('id'))->where('username', '=', $args['username'])->and_where('password', '=', md5($args['password']))->execute();

		if (count($result > 0)) {
			return $response->withJson($result, 200, JSON_PRETTY_PRINT);
		}
	}
}