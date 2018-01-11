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
});

$app->group('/bodyweights', function() {
	$this->get('/{id}', 'BodyweightController:getBodyweights');
});

$app->group('/lifttypes', function() {
	$this->get('/{id}', 'LifttypesController:getLifttypes');
});

$app->group('/foods', function() {
	$this->get('/{id}', 'FoodController:getFoods');
});

$app->group('/users', function() {
	$this->post('/checkLogin', 'UserController:checkLogin');
});