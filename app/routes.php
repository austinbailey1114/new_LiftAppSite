<?php

$app->group('/', function() {
	$this->get('', 'DashboardController:index');
	$this->get('login', 'DashboardController:login');
	$this->post('verify', 'DashboardController:verify');
	$this->get('logout', 'DashboardController:logout');
});

$app->group('/lifts', function() {
	$this->get('/{id}', 'LiftController:getLifts');
	$this->get('/view/asTable', 'LiftController:showLiftTable');
	$this->post('/', 'LiftController:postLift');
});

$app->group('/bodyweights', function() {
	$this->get('/{id}', 'BodyweightController:getBodyweights');
	$this->get('/view/asTable', 'BodyweightController:showBodyweightTable');
});

$app->group('/lifttypes', function() {
	$this->get('/{id}', 'LifttypesController:getLifttypes');
});

$app->group('/foods', function() {
	$this->get('/{id}', 'FoodController:getFoods');
	$this->get('/search/{query}', 'FoodController:searchFoods');
});

$app->group('/users', function() {
	$this->post('/checkLogin', 'UserController:checkLogin');
});