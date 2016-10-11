<?php
	
$api = app('Dingo\Api\Routing\Router');

$api->version('v1', function ($api) {

	//Users
	$api->post('auth/login', 'App\Api\V1\Controllers\AuthController@login');
	$api->post('auth/signup', 'App\Api\V1\Controllers\AuthController@signup');
	$api->post('auth/recovery', 'App\Api\V1\Controllers\AuthController@recovery');
	$api->post('auth/reset', 'App\Api\V1\Controllers\AuthController@reset');

	$api->group(['middleware' => 'api.auth'], function ($api) {
	
		//Devices
		$api->get('devices', 'App\Api\V1\Controllers\DeviceController@index');
		$api->get('books/{id}', 'App\Api\V1\Controllers\DeviceController@show');
		$api->post('devices', 'App\Api\V1\Controllers\DeviceController@store');
		$api->put('devices/{id}', 'App\Api\V1\Controllers\DeviceController@update');
		$api->delete('devices/{id}', 'App\Api\V1\Controllers\DeviceController@destroy');

		//Licenses
		$api->get('licenses', 'App\Api\V1\Controllers\LicenseController@index');
		$api->get('licenses/{id}', 'App\Api\V1\Controllers\LicenseController@show');
		$api->post('licenses', 'App\Api\V1\Controllers\LicenseController@store');
		$api->put('licenses/{id}', 'App\Api\V1\Controllers\LicenseController@update');
		$api->delete('licenses/{id}', 'App\Api\V1\Controllers\LicenseController@destroy');

		//Topics
		$api->get('topics', 'App\Api\V1\Controllers\TopicController@index');
		$api->get('topics/{id}', 'App\Api\V1\Controllers\TopicController@show');
		$api->post('topics', 'App\Api\V1\Controllers\TopicController@store');
		$api->put('topics/{id}', 'App\Api\V1\Controllers\TopicController@update');
		$api->delete('topics/{id}', 'App\Api\V1\Controllers\TopicController@destroy');

		//Beliefs
		$api->get('beliefs', 'App\Api\V1\Controllers\BeliefController@index');
		$api->get('beliefs/{id}', 'App\Api\V1\Controllers\BeliefController@show');
		$api->post('beliefs', 'App\Api\V1\Controllers\BeliefController@store');
		$api->put('beliefs/{id}', 'App\Api\V1\Controllers\BeliefController@update');
		$api->delete('beliefs/{id}', 'App\Api\V1\Controllers\BeliefController@destroy');

		//Verses
		$api->get('verses', 'App\Api\V1\Controllers\VerseController@index');
		$api->get('verses/{id}', 'App\Api\V1\Controllers\VerseController@show');
		$api->post('verses', 'App\Api\V1\Controllers\VerseController@store');
		$api->put('verses/{id}', 'App\Api\V1\Controllers\VerseController@update');
		$api->delete('verses/{id}', 'App\Api\V1\Controllers\VerseController@destroy');
	});

});


