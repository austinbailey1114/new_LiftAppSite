<?php

$app->get('/', 'DashboardController:index');

$app->group('/lifts', function() {
	$this->get('/{id}', 'LiftController:getLifts');
});