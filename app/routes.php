<?php

$app->group('/', function() {
	$this->get('', 'DashboardController:index');
	$this->get('login', 'DashboardController:login');
	$this->post('verify', 'DashboardController:verify');
	$this->get('logout', 'DashboardController:logout');
});

$app->group('/lifts', function() {
	//app pages
	$this->get('/view/asTable', 'LiftController:showLiftTable');
	$this->post('/addLift', 'LiftController:addLift');
});

$app->group('/bodyweights', function() {
	//app pages
	$this->get('/view/asTable', 'BodyweightController:showBodyweightTable');
});

$app->group('/lifttypes', function() {
	//app pages
});

$app->group('/foods', function() {
	$this->get('/{id}', 'FoodController:getFoods');
	$this->get('/search/{query}', 'FoodController:searchFoods');
});

$app->group('/users', function() {
	$this->post('/checkLogin', 'UserController:checkLogin');
});

$app->group('/api', function() {
	$this->group('/lifts', function() {
		$this->get('/{id}', 'LiftController:getLifts');
		$this->post('/', 'LiftController:postLift');
	});
	$this->group('/bodyweights', function() {
		$this->get('/{id}', 'BodyweightController:getBodyweights');
		$this->post('/', 'BodyweightController:postBodyweight');
	});
	$this->group('/lifttypes', function() {
		$this->get('/{id}', 'LifttypesController:getLifttypes');
		$this->post('/', 'LifttypesController:postLiftType');
	});
	$this->group('/foods', function() {
		$this->get('/{id}', 'FoodController:getFoods');
		$this->get('/search/{query}', 'FoodController:searchFoods');
		//todo when nutrition api is up
	});


});