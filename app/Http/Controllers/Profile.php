<?php namespace App\Http\Controllers;

use App\Category;
use App\Provider;
use App\ProviderPivot;
use App\Subcategory;
use App\User;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Image;
use JavaScript;
use Response;

/**
 * Class Profile
 * @package App\Http\Controllers
 */
class Profile extends Controller
{
    function __construct()
    {
        $this->middleware('auth', ['except' => 'show']);
        // TODO: Implement __construct() method.
    }


    private function getYoutubeIdFromUrl($url)
    {
        $parts = parse_url($url);
        if (isset($parts['query'])) {
            parse_str($parts['query'], $qs);
            if (isset($qs['v'])) {
                return $qs['v'];
            } else if (isset($qs['vi'])) {
                return $qs['vi'];
            }
        }
        if (isset($parts['path'])) {
            $path = explode('/', trim($parts['path'], '/'));
            return $path[count($path) - 1];
        }
        return false;
    }


    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        if (Auth::user()->hasRole('Provider')) {
            return \Redirect::to('/profile/' . Auth::user()->provider->id);
        } else {
            return \Redirect::to('/user');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $select_category_name = Category::all()->sortBy('name', SORT_ASC);
        $select_subcategory_name = "";
        return view('profile.setup', compact('select_category_name', 'select_subcategory_name'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        $license = Input::get('license');
        $insurance = Input::get('insurance');
        $handyman = Input::get('handyman');
        $categories = explode(',', \Input::get('categories'));
        $subcategories = explode(",", \Input::get('subcategories'));

        $rules = [
            'name' => 'required',
            'range' => 'required'
        ];

        $file = "";

        $validator = \Validator::make(\Input::all(), $rules);

        if ($validator->fails()) {
            return \Redirect::route('profile.create')->withErrors($validator);
        } else {

            if (Input::get('rangeValue') == "" || empty(Input::get('rangeValue'))) {
                $range = Input::get('range');
            } else {
                $range = Input::get('rangeValue');
            }


            $provider = Provider::updateOrCreate([
                'user_id' => Auth::id()
            ], [
                'user_id' => Auth::id(),
                'name' => \Input::get('name'),
                'range' => $range,
                'youtube' => $this->getYoutubeIdFromUrl(Input::get('youtube')),
                'website' => \Input::get('website'),
                'testimonial' => \Input::get('testimonial'),
                'logo' => $file == '' ? '' : $file->getClientOriginalName() . md5(Auth::id()),
                'note' => \Input::get('note'),
                'license' => $license,
                'insurance' => $insurance,
                'handyman' => $handyman
            ]);

            if (\Input::hasFile('logo')) {
                $file = \Input::file('logo');
                $upload_file = md5($file->getClientOriginalName() . Auth::user()->email) . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/users/logos'), $upload_file);
                Image::make(sprintf('uploads/users/logos/%s', $upload_file))->resize(200, 200)->save();
                $pro = Provider::find(\DB::getPdo()->lastInsertId());
                $pro->logo = $upload_file;
                $pro->save();
            } else {
                $file = '';
            }

            $last_inserted_id = $provider->id;

            $p = ProviderPivot::updateOrCreate([
                'category_id' => json_encode(serialize($categories)),
                'subcategory_id' => json_encode(serialize($subcategories)),
                'provider_id' => $last_inserted_id
            ],
                [
                    'category_id' => json_encode(serialize($categories)),
                    'subcategory_id' => json_encode(serialize($subcategories)),
                    'provider_id' => $last_inserted_id
                ]);

            $user = User::find(Auth::id());

            if ($user->hasRole('User') and $user->hasRole('Provider')) {

            } else {
                $user->roles()->attach(3);
            }

            //echo count($subcategories);

            \Session::flash('message', 'Successfully Registered As Provider');
            return \Redirect::to('/plan/show');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function show($id)
    {
        try {
            $profile = Provider::whereId($id)->with('ppivot')->with(['projects' => function ($query) use ($id) {
                return $query->where('provider_id', '=', $id);
            }])->get();
            $category_subcategories = ProviderPivot::whereProviderId($id)->get();
            $catsubcats = [];
            foreach ($category_subcategories as $catsubcat) {
                $catsubcats ['cat'][] = $catsubcat->category_id;
                $catsubcats ['subcat'][] = $catsubcat->subcategory_id;
            }
            $data_cat = json_decode($catsubcats['cat'][0]);
            $c = unserialize($data_cat);

            if (count($c) > 1) {
                $implode_cat_id = implode(",", $c);
            } else {
                $r = unserialize(json_decode($catsubcats['cat'][0]));
                $implode_cat_id = $r[0];
            }

            $data_subcat = json_decode($catsubcats['subcat'][0]);
            $sc = unserialize($data_subcat);
            if (count($sc) > 1) {
                $implode_subcat_id = implode(",", unserialize($data_subcat));
            } else {
                $rs = unserialize(json_decode($catsubcats['subcat'][0]));
                $implode_subcat_id = $rs[0];
            }

            if ($implode_cat_id == "" || empty($implode_cat_id) || $implode_subcat_id == "" || $implode_subcat_id == "") {
                $select_category_name = "";
                $select_subcategory_name = "";
            } else {
                $select_category_name = Category::whereRaw('id IN(' . $implode_cat_id . ')')->get(['name']);
                $select_subcategory_name = Subcategory::whereRaw('id IN(' . $implode_subcat_id . ')')->get(['name']);
            }

            $projects = \DB::select("SELECT projects.id,
            projects.categories_id,
            projects.subcategories_id,
            projects.user_id,
            projects.title,
            projects.body,
            projects.image,
            projects.cost,
            projects.ended,
            projects.est_time,
            projects.updated_at,
            projects.created_at,
            provider_client.id,
            provider_client.provider_id,
            provider_client.user_id AS 'client_id',
            provider_client.project_id,
            provider_client.applied,
            provider_client.created_at AS 'pcdate',
            provider_client.updated_at AS 'pcupdate'
        FROM provider_client INNER JOIN projects ON provider_client.project_id = projects.id where provider_client.provider_id = ? AND projects.ended = 0",
                [$id]);


            return view('profile.show', compact('profile', 'select_category_name', 'select_subcategory_name', 'projects'));
        } catch (\ErrorException $e) {
            return view('partial.error');
        }
    }

    public function projects()
    {
        if (Auth::user()->hasRole('Provider')) {
            $projects = \DB::select("SELECT projects.id,
            projects.categories_id,
            projects.subcategories_id,
            projects.user_id,
            projects.title,
            projects.body,
            projects.image,
            projects.cost,
            projects.ended,
            projects.est_time,
            projects.updated_at,
            projects.created_at,
            provider_client.id,
            provider_client.provider_id,
            provider_client.user_id AS 'client_id',
            provider_client.project_id,
            provider_client.applied,
            provider_client.created_at AS 'pcdate',
            provider_client.updated_at AS 'pcupdate'
        FROM provider_client INNER JOIN projects ON provider_client.project_id = projects.id where provider_client.provider_id = ? AND projects.ended = 0 ORDER BY projects.created_at DESC", [\Auth::user()->provider->id]);
            return view('profile.projects', compact('projects'));
        } else {
            return \Redirect::to('/user');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return Response
     */


    public function edit($id)
    {
        //https://maps.googleapis.com/maps/api/distancematrix/json?origins=ludhiana&destinations=jalandhar&sensor=false
        // Use full
        $provider = Provider::find($id);
        if ($provider->id != Auth::user()->provider->id) {
            return \Redirect::to('/');
        } else {
            $categories = Category::all()->sortBy('name', SORT_ASC);
            $category_subcategories = ProviderPivot::whereProviderId(Auth::user()->provider->id)->get();

            $catsubcats = [];
            foreach ($category_subcategories as $catsubcat) {
                $catsubcats ['cat'][] = $catsubcat->category_id;
                $catsubcats ['subcat'][] = $catsubcat->subcategory_id;
            }

            if (count($catsubcats) > 0) {
                $data_cat = json_decode($catsubcats['cat'][0]);

                $c = unserialize($data_cat);

                if (count($c) > 1) {
                    $implode_cat_id = implode(",", $c);
                } else {
                    $r = unserialize(json_decode($catsubcats['cat'][0]));
                    $implode_cat_id = $r[0];
                }

                $data_subcat = json_decode($catsubcats['subcat'][0]);
                $sc = unserialize($data_subcat);
                if (count($sc) > 1) {
                    $implode_subcat_id = implode(",", unserialize($data_subcat));
                } else {
                    $rs = unserialize(json_decode($catsubcats['subcat'][0]));
                    $implode_subcat_id = $rs[0];
                }

                if ($implode_cat_id == "" || empty($implode_cat_id) || $implode_subcat_id == "" || $implode_subcat_id == "") {
                    $cat_names = [];
                    $cat_id = [];
                    $subcat_names = [];
                    $subcat_id = [];
                    $select_category_name = Category::all();
                    $select_subcategory_name = "";
                    return view('profile.edit', compact('provider', 'categories', 'select_category_name', 'select_subcategory_name', 'cat_names', 'subcat_names', 'cat_id', 'subcat_id'));
                } else {
                    $select_category_name = Category::whereRaw('id IN(' . $implode_cat_id . ')')->get(['id', 'name']);
                    $select_subcategory_name = Subcategory::whereRaw('id IN(' . $implode_subcat_id . ')')->get(['id', 'name']);
                    $cat_names = [];
                    $cat_id = [];
                    $subcat_names = [];
                    $subcat_id = [];

                    foreach ($select_category_name as $select_cat_name) {
                        $cat_names [] = $select_cat_name->name;
                        $cat_id [] = $select_cat_name->id;
                    }

                    foreach ($select_subcategory_name as $select_subcat_name) {
                        $subcat_names [] = $select_subcat_name->name;
                        $subcat_id [] = $select_subcat_name->id;
                    }

                    if (count($cat_names) > 0 and count($subcat_names) > 0) {
                        JavaScript::put(
                            [
                                'data_cat_names' => $cat_names,
                                'data_cat_id' => $cat_id,
                                'data_subcat_names' => $subcat_names,
                                'data_subcat_id' => $subcat_id
                            ]
                        );
                    }


                    //dd($select_category_name);

                    return view('profile.edit', compact('provider', 'categories', 'select_category_name', 'select_subcategory_name', 'cat_names', 'subcat_names', 'cat_id', 'subcat_id'));
                }
            } else {
                $categories = Category::all()->sortBy('name', SORT_ASC);
                $category_subcategories = ProviderPivot::whereProviderId(Auth::user()->provider->id)->get();

                $catsubcats = ['cat' => [], 'subcat' => []];
                foreach ($category_subcategories as $catsubcat) {
                    $catsubcats ['cat'][] = $catsubcat->category_id;
                    $catsubcats ['subcat'][] = $catsubcat->subcategory_id;
                }

                if(count($catsubcats['cat']) > 0) {
                    $data_cat = json_decode($catsubcats['cat'][0]);

                    $c = unserialize($data_cat);

                    if (count($c) > 1) {
                        $implode_cat_id = implode(",", $c);
                    } else {
                        $r = unserialize(json_decode($catsubcats['cat'][0]));
                        $implode_cat_id = $r[0];
                    }

                    $data_subcat = json_decode($catsubcats['subcat'][0]);
                    $sc = unserialize($data_subcat);
                    if (count($sc) > 1) {
                        $implode_subcat_id = implode(",", unserialize($data_subcat));
                    } else {
                        $rs = unserialize(json_decode($catsubcats['subcat'][0]));
                        $implode_subcat_id = $rs[0];
                    }

                    $select_category_name = Category::whereRaw('id IN(' . $implode_cat_id . ')')->get(['id', 'name']);
                    $select_subcategory_name = Subcategory::whereRaw('id IN(' . $implode_subcat_id . ')')->get(['id', 'name']);
                    $cat_names = [];
                    $cat_id = [];
                    $subcat_names = [];
                    $subcat_id = [];

                    foreach ($select_category_name as $select_cat_name) {
                        $cat_names [] = $select_cat_name->name;
                        $cat_id [] = $select_cat_name->id;
                    }

                    foreach ($select_subcategory_name as $select_subcat_name) {
                        $subcat_names [] = $select_subcat_name->name;
                        $subcat_id [] = $select_subcat_name->id;
                    }

                    if (count($cat_names) > 0 and count($subcat_names) > 0) {
                        JavaScript::put(
                            [
                                'data_cat_names' => $cat_names,
                                'data_cat_id' => $cat_id,
                                'data_subcat_names' => $subcat_names,
                                'data_subcat_id' => $subcat_id
                            ]
                        );
                    }
                } else {
                    $categories = Category::all()->sortBy('name', SORT_ASC);
                    $select_category_name = [];
                    $select_subcategory_name = [];
                    $cat_names = [];
                    $subcat_names = [];
                    $cat_id = [];
                    $subcat_id = [];
                    return view('profile.edit', compact('provider', 'cat_names', 'subcat_names', 'cat_id', 'subcat_id', 'select_category_name', 'select_subcategory_name', 'categories'));
                }

                return view('profile.edit', compact('provider', 'categories', 'select_category_name', 'select_subcategory_name', 'cat_names', 'subcat_names', 'cat_id', 'subcat_id'));
            }
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int $id
     * @return Response
     */
    public function update($id, Request $request)
    {
        $provider = Provider::find($id);
        $categories = explode(',', \Input::get('categories'));
        $subcategories = explode(",", \Input::get('subcategories'));

        if (strlen(Input::get("website") > "1")) {
            $validator = \Validator::make([
                'website' => Input::get('website')
            ], [
                'website' => 'regex:/^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/'
            ]);
            if ($validator->fails()) {
                return \Redirect::back()->withErrors($validator->errors());
            }
        }

        $last_inserted_id = $provider->id;

        $provider_pivot = ProviderPivot::updateOrCreate([
            'provider_id' => $provider->id
        ],[
            'category_id' => json_encode(serialize($categories)),
            'subcategory_id' => json_encode(serialize($subcategories))
        ]);

        $range = "";

        if (Input::get('rangeValue') == "" || empty(Input::get('rangeValue'))) {
            $range = Input::get('range');
        } else {
            $range = Input::get('rangeValue');
        }

        if (\Input::file('logo') != "") {
            $file = \Input::file('logo');
            $upload_file = md5($file->getClientOriginalName() . Auth::user()->email) . "." . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/users/logos'), $upload_file);
            $image = Image::make(sprintf('uploads/users/logos/%s', $upload_file))->resize(200, 200)->save();
            $provider->logo = $upload_file;
            $provider->save();
        }

        $provider->name = \Input::get('name');
        $provider->range = $range;
        $provider->website = \Input::get('website');
        $provider->testimonial = \Input::get('testimonial');
        $provider->youtube = $this->getYoutubeIdFromUrl(\Input::get('youtube'));
        $provider->note = \Input::get('note');
        $provider->license = \Input::get('license');
        $provider->insurance = \Input::get('insurance');
        $provider->handyman = \Input::get('handyman');
        $provider->save();

        return \Redirect::to('/profile');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return Response
     */
    public function destroy($id)
    {
        return view('profile.delete');
    }

    public function UserProfileEdit()
    {
        $user = User::with('userinfo')->whereId(Auth::id())->get();
        return view('profile.edituser', compact('user'));
    }

    public function UserProfileEditPost()
    {
        $user = User::with('userinfo')->find(Auth::id());

        $user->userinfo->country = Input::get('country');
        $user->userinfo->state = Input::get('state');
        $user->userinfo->city = Input::get('city');
        $user->userinfo->current_place = Input::get('current_place');
        if (Input::get('zipcode') == "" || empty(Input::get('zipcode')) || Input::get('zipcode') == "0") {
            $user->userinfo->zip_code = Input::get('zipcode_field');
        } else {
            $user->userinfo->zip_code = Input::get('zipcode');
        }
        $user->userinfo->latitude = Input::get('latitude');
        $user->userinfo->longitude = Input::get('longitude');
        $user->userinfo->address = Input::get('address');
        $user->userinfo->address2 = Input::get('address2');
        if ($user->userinfo->save()) {
            return \Redirect::to('/profile');
        } else {
            return \Redirect::to('/');
        }
    }

}