<?php

namespace App\Http\Controllers;

use App\Category;
use App\Provider;
use App\Subcategory;
use App\User;
use Auth;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Laravel\Socialite\Two\InvalidStateException;
use Response;

class Main extends Controller {

	protected $fields;
	/*
		    |--------------------------------------------------------------------------
		    | Welcome Controller
		    |--------------------------------------------------------------------------
		    |
		    | This controller renders the "marketing page" for the application and
		    | is configured to only allow guests. Like most of the other sample
		    | controllers, you are free to modify or remove it as you desire.
		    |
	*/

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct() {
		$this->middleware('VerifyAC', ['expect' => 'guest']);
		if (\Input::has('q')) {
			$this->fields = \Input::get('q');
		}
	}

	/**
	 * Show the application welcome screen to the user.
	 *
	 * @return Response
	 */
	public function loginWithFacebook() {
		return \Socialize::with('facebook')->redirect();

	}

	public function loginWithGoogle() {
		return \Socialize::with('google')->redirect();
	}

	public function AfterLogin() {
		try {
			$user = \Socialize::with('facebook')->user();

			$regx_email = "/[a-zA-Z0-9_.+-]+/";
			$email = [];
			$email_result = preg_match($regx_email, $user->email, $email);

			if (User::where('email', '=', $user->email)->first()) {
				$checkUser = User::where('email', '=', $user->email)->first();
				Auth::login($checkUser);
				return \Redirect::to('/user');
			}

			$user_save = User::create([
				'facebook_id' => $user->id,
				'name' => $user->name,
				'username' => $email[0],
				'email' => $user->email,
				'avatar' => $user->avatar,
				'activate' => 1,
			]);

			$user_save->userinfo()->create([
				'user_id' => $user_save->id,
			]);

			$user_save->roles()->attach(1);

			Auth::login($user_save);
			return redirect('/');
		} catch (\Exception $e) {
			if ($e instanceof ClientException or $e instanceof InvalidStateException) {
				return \Redirect::to('/auth/login');
			}
		}
	}

	public function AfterLoginGoogle() {
		try {
			$user = \Socialize::with('google')->user();
			if (User::where('email', '=', $user->email)->first()) {
				$checkUser = User::where('email', '=', $user->email)->first();
				Auth::login($checkUser);
				return redirect('/user');
			}

			$user_save = User::create([
				'google_id' => $user->getId(),
				'name' => $user->getName(),
				'email' => $user->getEmail(),
				'avatar' => $user->getAvatar(),
				'activate' => 1,
			]);

			$user_save->userinfo()->create([
				'user_id' => $user_save->id,
			]);

			$user_save->roles()->attach(1);

			Auth::login($user_save);
			return redirect('/');
		} catch (\Exception $e) {
			if ($e instanceof ClientException or $e instanceof InvalidStateException) {
				return \Redirect::to('/auth/login');
			}
		}
	}

	public function search() {
		$query_word = \Input::get('query');

		try {
			$category = Category::whereName($query_word)->get();

			$ids = [];
			foreach ($category as $cat) {
				$ids[] = $cat->id;
			}

			$id = implode(',', $ids);
			$subcategory = Subcategory::whereRaw('category_id IN(' . $id . ')')->get();
			return view('main.search', compact('subcategory'));
		} catch (QueryException $e) {
			$subcategory = [];
			return view('main.search', compact('subcategory'));
		}
	}

	public function buy() {
		return view('main.buy');
	}

	public function index(Request $request) {
		$cats = \DB::table('categories')->select("*")->orderBy('name', 'asc')->simplePaginate(15);
		return view('main.main', compact('cats'));
	}

	public function Show($name, $id) {
		$categories = Subcategory::where('category_id', '=', $id)->simplePaginate(12);
		$cat = Category::find($id);
		return view('main.show')->with("categories", $categories)->with('c', $cat);
	}

	public function Query() {
		$result = Category::where('name', 'LIKE', '%' . \Input::get('q') . '%')->get(['id', 'name']);
		return Response::make($result);
	}

	public function SubQuery() {
		$cat_id = Provider::where("user_id", '=', \Auth::id())->get(['category_id']);
		$ids = [];

		foreach ($cat_id as $cat) {
			$ids[] = $cat->category_id;
		}
		$imp = implode(',', $ids);
		$result = DB::table('subcategories')->whereRaw('category_id IN(' . $imp . ')')->where('name', 'LIKE',
			'%' . \Input::get('q') . '%')->get(['id', 'name']);

		return Response::make($result);
	}

	public function SaveQuery() {
		$categories_ids = \Input::get('q');
		$subcategories_ids = \Input::get('id');

		$pro = Provider::firstOrCreate([
			'user_id' => \Auth::id(),
		]);

		$existUser = Provider::whereUserId(\Auth::id())->get();

		foreach ($existUser as $user) {
			$u = Provider::find($user->id);
			$u->category_id = $categories_ids;
			$u->subcategory_id = $subcategories_ids;
			$u->save();
		}

		return $subcategories_ids;

	}

	public function PreCat() {
		$ids = Provider::whereUserId(Auth::id())->get(['category_id', 'subcategory_id'])->toArray();
		if ($ids[0]['category_id'] == "" || empty($ids[0]['category_id'])) {
			$catsById = [];
		} else {
			$catsById = Category::whereRaw('id IN (' . $ids[0]['category_id'] . ')')->get(['id', 'name']);
		}
		return Response::make($catsById);
	}

	public function PreSubCat() {
		$ids = Provider::whereUserId(Auth::id())->get(['category_id', 'subcategory_id'])->toArray();
		if (empty($ids[0]['subcategory_id'])) {
			$subcatsById = [];
		} else {
			$subcatsById = Category::whereRaw('id IN (' . $ids[0]['subcategory_id'] . ')')->get(['id', 'name']);
		}
		return Response::make($subcatsById);
	}

	public function subcategories() {
		echo "yes";
	}

}
