<?php

$app->get('/', 'DashboardController:index');

$app->group('/lifts', function() {
	$this->get('/{id}', 'LiftController:getLifts');
});

$app->group('/bodyweights', function() {
	$this->get('/{id}', 'BodyweightController:getBodyweights');
});