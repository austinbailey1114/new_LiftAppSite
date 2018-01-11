<?php

$app->get('/', 'DashboardController:index');

$app->group('/lifts', function() {
	$this->get('/{id}', 'LiftController:getLifts');
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