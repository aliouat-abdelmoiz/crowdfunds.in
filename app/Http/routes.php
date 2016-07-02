<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
 */

use App\User;

// counters

Route::get("/page/{name}", 'PageController@page');

Route::get('sitemap', 'Api@sitemap');

Route::get("email", function () {
	return view('emails.client');
});

Route::get('/messages', 'Api@eventStream');
Route::get('/notification', 'Api@notificationStream');
Route::get('/images-load', 'Api@getPaidUsersLogo');
Route::get('/decodejson', 'Api@decode_json');
Route::get('/contact', function () {
	return view('main.contact');
});
Route::post('/contact', function () {
	$backup = Mail::getSwiftMailer();
	$transport = Swift_SmtpTransport::newInstance('smtp.yandex.com', 465, 'ssl');
	$transport->setUsername('support@yourserviceconnection.com');
	$transport->setPassword('p@$$w0rd');
// Any other mailer configuration stuff needed...

	$yandax = new Swift_Mailer($transport);

// Set the mailer as gmail
	Mail::setSwiftMailer($yandax);

	$mail_name = Request::get("fullname");
	$email_name = Request::get("email") == "" ? "no-email@blank.com" : Request::get("email");
	$subject_name = Request::get("subject");
	$message_name = Request::get("message");

	Mail::send('emails.support', ["name" => $mail_name, "subject_name" => $subject_name, "message_name" => $message_name], function ($message) use ($email_name, $subject_name) {
		$message->to('support@yourserviceconnection.com');
		$message->subject($subject_name);
		$message->from('support@yourserviceconnection.com');
		$message->replyTo($email_name);
	});

	Mail::setSwiftMailer($backup);

});
Route::get('/jobpost', function () {
	return view('main.job-done');
});

// End Site Map

Route::get('/', 'Main@index');
Route::get('/buy', 'Main@buy');
Route::post('/buy', function () {
	dd(Input::all());
});
Route::get('/home', 'Main@index');
Route::get('/test', 'Api@suggest');
Route::get('/new', function () {
	$params = [
		'hosts' => 'mandeep:p@$$w0rd@192.168.1.200:9200',
	];
	$client = \Elasticsearch\ClientBuilder::create()->setHosts($params)->build();

	$index = [
		'body' => [
			'index' => 'renowned',
			'type' => 'basic',
			'name' => 'mandeep',
			'description' => 'i am developer',
		],
	];

	$client->create($index);

});
Route::get('/search/{query?}', 'Main@search');
Route::get('/Items/{name}/{id}', 'Main@Show');
Route::get('/single/{id}', 'Main@Single');
Route::get('/fb/login', 'Main@loginWithFacebook');
Route::get('/afterlogin', 'Main@AfterLogin');
Route::get('/google/auth', 'Main@loginWithGoogle');
Route::get('/afterLoginGmail', 'Main@AfterLoginGoogle');
Route::get('/zip/', 'Api@getLocationByZip');
Route::get('/image', function () {
	return "Image";
});
Route::get('/change', function () {
	return view('auth.reset');
});
Route::get('/policy', function () {
	return \Carbon\Carbon::now();
});

Route::get('jobs/rating/{id}', 'Jobs@getRating');
Route::get('jobs/show/{id}', 'Jobs@Show');
Route::get('jobs/{category?}/{subcategory?}/{id}/{id2}{premium?}', 'Jobs@index');
Route::get('jobs', ['as' => 'jobs', 'uses' => 'Jobs@index']);
Route::post('jobs', ['as' => 'jobs.post', 'uses' => 'Jobs@JobPost']);
Route::post('/file/post', 'Api@PostFile');

Route::group(['middleware' => ['auth', 'UserRoles', 'VerifyAC']], function () {
	Route::resource('notifications', 'NotificationController');
	Route::resource('user', 'UserController');
	Route::get('/password/change', 'UserController@changePassword');
	Route::post('/password/change', 'UserController@ResetPassword');
	Route::get('profile', 'Profile@index');
	Route::get('projects', 'Profile@projects');
	Route::get('/message', 'MessageController@index');
	Route::get('/account/delete', function () {
		User::find(Auth::id())->delete();
		return Redirect::to('/auth/login');
	});
	Route::get('/account/verify', 'Api@getVerify');
	// Plans Routes
	Route::get('/plan/show', 'PlanController@show');
	Route::post('/plan/create', 'PlanController@create');
	Route::post('/plan/checkout', 'PlanController@checkout');
	Route::get('/premium/{id}/{plan}/edit', 'AdvertiseController@edit');
	Route::resource('premium', 'AdvertiseController');
	Route::resource('/advertise/admin', 'AdvertiseController@admin');
	Route::group(['middleware' => 'JobsMW'], function () {
		Route::post('jobs/hire', 'Jobs@hire');
		Route::post('jobs/end', 'Jobs@endContract');
		Route::post('jobs/delete', 'Jobs@destroy');
	});
	Route::get('profile/edituser', 'Profile@UserProfileEdit');
	Route::post('profile/edituser', 'Profile@UserProfileEditPost');
	Route::get('message/sent', 'MessageController@sent');
	Route::get('/user/conversation', 'MessageController@getConversation');
	Route::get('/user/messages/{id?}', 'MessageController@getMessages');
	Route::get('/markAsRead', 'Api@MarkMessageAsRead');
	Route::get('/markAsUnread', 'Api@MarkMessageAsUnread');
	Route::get('/messages/count', 'Api@getMessageCount');
	Route::resource('message', 'MessageController');
	Route::get('/hire/{id}', function () {
		if (Auth::check()) {
			if (Auth::user()->hasRole('User')) {
				$provider_client = \DB::table('provider_client')->update([
					'provider_id' => \Input::get('provider_id'),
				])->where('project_id', '=', '')->where('user_id', '=', Auth::id());
			} else {
				return Redirect::to('/profile');
			}
		} else {
			return Redirect::to('/');
		}
	});
});

Route::resource('profile', 'Profile');
Route::get('/messageNotifcation', 'Api@messageNotifcation');

Route::group(['prefix' => 'api'], function () {
	Route::get('/category_name/{id}', 'Api@GetCategoryName');
	Route::get('/subcategory_name/{id}', 'Api@GetSubcategoryName');
	Route::post('/makereadnotify', 'Api@makereadnotify');
	Route::get('/search', 'Api@search');
	Route::get('/decode', 'Api@decode');
	Route::get('/image', 'Api@getPremiumUserImpression');
	Route::get('user/{id}', 'Api@getUserNameById');
	Route::post('/user/sendmessage', 'Api@sendMessage');
	Route::post('/user/replymessage', 'Api@replyMessage');
	Route::get('/messages/count', 'Api@getMessageCount');
	Route::get('getproject/{id?}', 'Api@getProjectsById');
	Route::post('updateProjectWhenUserHired', 'Api@updateProjectWhenUserHired');
	Route::post('hireUserForProject', 'Api@hireUserForProject');
	Route::get('query', 'Main@Query');
	Route::get('subquery', 'Main@SubQuery');
	Route::get('savequery', 'Main@SaveQuery');
	Route::get('preCat', 'Main@PreCat');
	Route::get('preSubCat', 'Main@PreSubCat');
	Route::get('subcategories', 'Api@getsubcats');
	Route::get('getreplies', 'Api@checkReply');
	// Advertise Routes
	Route::get('/premium', 'Api@ShowAdvertise');
	Route::post('/chargeimpression', 'Api@MakeImppression');
	Route::post('/chargeclick', 'Api@MakeClick');
	Route::post('/stopadv', 'Api@stopAdvertise');
	Route::get('/get_impressions', 'Api@GetImpressions');
	Route::get('/loginwithid', 'Api@LoginWithId');
	Route::get('/gettoken/{key}', 'Api@GetToken');
	Route::post('/subscribe', 'Api@subscribe');
	Route::post('/unsubscribe', 'Api@unsubscribe');
});

Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);

Route::get('/page/jobs/{country?}/', 'PageController@country');
Route::get('/page/jobs/{country?}/{state?}/', 'PageController@state');
Route::get('/page/jobs/{country?}/{state?}/{city?}', 'PageController@city');

Route::group(['prefix' => 'test'], function () {
	Route::get('/userslist', 'TestApiController@CheckNearByUsers');
});
