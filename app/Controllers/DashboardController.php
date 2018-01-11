<?php

namespace Carbon\Controllers;

class DashboardController extends Controller {
	public function index($request, $response) {
		return $this->view->render($response, 'index.php');
	}
}