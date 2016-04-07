<?php

return [

	/*
	|--------------------------------------------------------------------------
	| oAuth Config
	|--------------------------------------------------------------------------
	*/

	/**
	 * Storage
	 */
	'storage' => 'Session',

	/**
	 * Consumers
	 */
	'consumers' => [

		'Facebook' => [
			'client_id'     => '344429762299755',
			'client_secret' => '944b83c974818dfc34349e0bda37242c',
			'scope'         => ['email','firstname','lastname'],
		],

	]

];