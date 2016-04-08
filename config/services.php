<?php

return [

	/*
	|--------------------------------------------------------------------------
	| Third Party Services
	|--------------------------------------------------------------------------
	|
	| This file is for storing the credentials for third party services such
	| as Stripe, Mailgun, Mandrill, and others. This file provides a sane
	| default location for this type of information, allowing packages
	| to have a conventional place to find your various credentials.
	|
	*/

	'mailgun' => [
		'domain' => 'https://api.mailgun.net/v3/crowdfunds.in',
		'secret' => 'key-48b075b3529318701a48639ce4e588ef',
	],

	'mandrill' => [
		'secret' => '',
	],

	'ses' => [
		'key' => '',
		'secret' => '',
		'region' => 'us-east-1',
	],

	'stripe' => [
		'model'  => 'App\User',
		'key' => env('STRIPE_PUBLIC'),
		'secret' => env('STRIPE_API_SECRET'),
	],

	'facebook' => [
		'client_id'     => '104428796577848',
		'client_secret' => '5e87b0f04852e4b439da52fbfe2951ae',
		'redirect'         => 'http://yourserviceconnection.com/afterlogin',
	],

    'google' => [
		'client_id'     => '861027737351-b2hemehpvh6erfu8gb3imf74649hgqqh.apps.googleusercontent.com',
		'client_secret' => 'q-eIIjs2FYZZ_QtCK1cRnPjP',
		'redirect'         => 'http://yourserviceconnection.com/afterLoginGmail',
	],

];
