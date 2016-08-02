<?php

/**
 * Created by PhpStorm.
 * User: mandeepgill
 * Date: 04/06/15
 * Time: 7:14 PM
 */

namespace App\Http\Controllers;

use App\Category;
use App\Notification;
use App\Project;
use App\Provider;
use App\ProviderClient;
use App\User;
use Auth;
use Carbon\Carbon;
use DB;
use Illuminate\Support\Facades\Input;

class Jobs extends Controller
{
    public function __construct()
    {

    }

    private function _MatchCategory()
    {
        $provider_cat = Provider::whereUserId(Auth::id())->get(['category_id', 'subcategory_id']);
        foreach ($provider_cat as $cat) {
            return $cat->category_id;
        }
    }

    public function index($category = null, $subcategory = null, $id = null, $id2 = null)
    {
        if (isset($_GET['premium']) || !empty(\Input::get('premium')) || \Input::get('premium') == "" || \Input::get('premium') == "true") {
            $premium = \Input::get('premium');
        } else {
            $premium = "x";
        }
        $seo_cat = \Request::get('category');

        if (\Auth::guest()) {
            \ZipCode::setCountry('US');
            $zip = file_get_contents('http://ip-api.com/json');
            $user = json_encode($zip);
            if ($category and $subcategory) {
                $categories = DB::table('categories')->select(['id', 'name'])->where('name', '=', $category)->orderBy('name')->lists('name', 'id');
                $cats = Category::find(Category::whereName($category)->get(['id'])[0]->id);
                $subcategories = $cats->subcategories()->get()->lists('name', 'id');
                return view('jobs.index', compact('categories', 'user', 'subcategories', 'category', 'subcategory', 'premium', 'seo_cat'));
            } else {
                return \Redirect::to('/');
            }
        } else {
            if ($category and $subcategory) {
                $categories = DB::table('categories')->select(['id', 'name'])->where('name', '=', $category)->orderBy('name')->lists('name', 'id');
                $cats = Category::find(Category::whereName($category)->get(['id'])[0]->id);
                $subcategories = $cats->subcategories()->get()->lists('name', 'id');
                return view('jobs.index', compact('categories', 'subcategories', 'user', 'category', 'subcategory', 'premium'));
            } else {
                $categories = DB::table('categories')->select(['id', 'name'])->orderBy('name')->lists('name', 'id');
                $user = User::find(\Auth::id());
                return view('jobs.index', compact('categories', 'user', 'premium', 'id'));
            }
        }
    }

    public function Show($id)
    {
        $project = Project::whereId($id)->whereUserId(\Auth::id())->first();
        $provider_client = \DB::table('provider_client')->where('project_id', '=', $id)->whereApplied(1)->get();
        $provider_ids = [];
        foreach ($provider_client as $client) {
            $provider_ids[] = $client->provider_id;
        }
        $providers = \DB::table('providers')->join('provider_client', 'providers.id', '=', 'provider_client.provider_id')->whereIn('providers.id', $provider_ids)->where('provider_client.applied', '=', 1)->where('provider_client.project_id', '=', $project->id)->get(['providers.*', 'provider_client.created_at as cdate', 'provider_client.updated_at as udate', 'provider_client.id as pcid', 'provider_client.project_id', 'provider_client.applied', 'provider_client.user_id as cuser_id']);
        return view('jobs.show', compact('project', 'providers'));
    }

    public function JobPost()
    {
        $count = 0;

        if (\Input::get('uid')) {
            $plans = User::find(\Input::get('uid'))->plans;

            $id = \Input::get('plan_id');
            $select_plan = $plans->each(function ($data) use ($id) {
                if ($data->id == $id) {
                    return true;
                } else {
                    return false;
                }
            });
        }

        if (\Input::get('premium') == 'true' and $select_plan == true) {
            $validator_guest = \Validator::make(\Request::all(), ['zip' => 'required|max:6', 'email' => 'required|email|unique:users', 'project_title' => 'required|max:250', 'description' => 'required', 'category' => 'required']);

            $validator = \Validator::make(\Request::all(), ['project_title' => 'required|max:250', 'description' => 'required', 'category' => 'required']);

            $api = new Api();

            $username = $api->ExtractUsername(\Input::get('email')) . rand(0, 5000) . 'yourserv';

            if (\Auth::guest()) {
                echo "registibng";
                exit();
                if ($validator_guest->fails()) {
                    return \Redirect::back()->withErrors($validator_guest->errors())->withInput();
                } else {
                    $password = rand(0, 600) . 'serv' . rand(0, 5);
                    $register = User::create(['username' => $username, 'email' => \Input::get('email'), 'password' => bcrypt($password)]);
                    $register->userinfo()->create(['country' => \Input::get('country'), 'zip_code' => \Input::get('zip'), 'latitude' => \Input::get('latitude'), 'longitude' => \Input::get('longitude'), 'city' => \Input::get('city'), 'state' => \Input::get('state'), 'user_id' => $register->id]);

                    Auth::loginUsingId($register->id);
                    \Auth::user()->attachRole(1);

                    if ($register) {
                        $project = Project::create(['categories_id' => \Input::get('category'), 'subcategories_id' => \Input::get('subcategory'), 'user_id' => \Auth::id(), 'title' => \Input::get('title'), 'body' => \Input::get('description'), 'range' => \Input::get('range'), 'premium' => 1, 'forward_date' => Carbon::now()->addDay(1)]);

                        $last_inserted_id = $project->id;

                        ProviderClient::create(['user_id' => \Auth::id(), 'provider_id' => User::find(\Input::get('uid'))->provider->id, 'project_id' => $last_inserted_id]);

                        $api->getVerify($password);
                        Auth::logout();
                        return view('main.job-done', compact('count'));
                    }
                }
            } else {
                if ($validator->fails()) {
                    return Redirect::back()->withInput();
                } else {
                    $project = Project::create(['categories_id' => \Input::get('category'), 'subcategories_id' => \Input::get('subcategory'), 'user_id' => \Auth::id(), 'title' => \Input::get('project_title'), 'body' => \Input::get('description'), 'range' => \Input::get('range'), 'premium' => 1, 'forward_date' => Carbon::now()->addDay(1)]);

                    $last_inserted_id = $project->id;

                    ProviderClient::create(['user_id' => \Auth::id(), 'provider_id' => User::find(\Input::get('uid'))->provider->id, 'project_id' => $last_inserted_id]);

                    return \Redirect::to('/profile');
                }
            }
        } else {
            $validator_guest = \Validator::make(\Request::all(), ['zip' => 'required|max:6', 'email' => 'required|email|unique:users', 'project_title' => 'required|max:250', 'description' => 'required', 'category' => 'required']);

            $validator = \Validator::make(\Request::all(), ['project_title' => 'required|max:250', 'description' => 'required', 'category' => 'required']);

            $api = new Api();

            $username = $api->ExtractUsername(\Input::get('email')) . rand(0, 5000) . 'yourserv';
            if (\Auth::guest()) {
                if ($validator_guest->fails()) {
                    return \Redirect::back()->withErrors($validator_guest->errors())->withInput();
                } else {
                    $zip = \Input::get('zip');
                    $category = \Input::get('category');
                    $subcategory = \Input::get('subcategory');

                    $provider = \DB::select('call GetCatSubcat("' . $category . '","' . $subcategory . '","' . Input::get('latitude') . '", "' . Input::get('longitude') . '", "' . Input::get('city') . '", "' . Input::get('state') . '")');

                    $password = rand(0, 600) . 'serv' . rand(0, 5);

                    if (!is_null($provider)) {
                        try {
                            $register = User::create(['username' => $username, 'email' => \Input::get('email'), 'password' => bcrypt($password)]);
                            $register->userinfo()->create(['country' => \Input::get('country'), 'zip_code' => \Input::get('zip'), 'latitude' => \Input::get('latitude'), 'longitude' => \Input::get('longitude'), 'city' => \Input::get('city'), 'state' => \Input::get('state'), 'user_id' => $register->id]);
                        } catch (\Exception $e) {
                            echo $e->getMessage();
                        }

                        Auth::loginUsingId($register->id);

                        \Auth::user()->attachRole(1);

                        if ($register) {
                            $project = Project::create(
                                ['categories_id' => \Input::get('category'), 'subcategories_id' => \Input::get('subcategory'), 'user_id' => \Auth::id(), 'title' => \Input::get('project_title'), 'body' => \Input::get('description'), 'range' => \Input::get('range')]);

                            $last_inserted_id = $project->id;
                            foreach ($provider as $select_provider) {
                                ProviderClient::create(['user_id' => \Auth::id(), 'provider_id' => $select_provider->provider_id, 'project_id' => $last_inserted_id]);
                                $count++;
                                \Mail::send('emails.job-notifcation', [\Input::get('project_title'), Input::get('description')], function ($message) use ($select_provider) {
                                    $message->to(Provider::find($select_provider->provider_id)->user->email)->subject('New Project Received');
                                    Notification::create([
                                        'text' => 'Received New Job ' . Input::get('project_title'),
                                        'link' => '/projects',
                                        'from' => Auth::user()->id,
                                        'user_id' => Provider::find($select_provider->provider_id)->user_id,
                                    ]);
                                });
                            }

                            $api->getVerify($password);

                            \Auth::logout();

                            if ($count > 0) {
                                \Session::flash("success", "Job Sent to " . $count . " Providers");
                            } else {
                                \Session::flash("success", "Job Sent to Provider");
                            }

                            return view('main.job-done', compact('count'));
                        }
                    }
                }
            } else {
                if ($validator->fails()) {
                    return \Redirect::to('/jobs')->withErrors($validator->errors())->withInput();
                } else {
                    $zip = \Auth::user()->userinfo->zip_code;

                    $category = \Input::get('category');
                    $subcategory = \Input::get('subcategory');

                    $provider = \DB::select('call GetCatSubcat("' . $category . '","' . $subcategory . '","' . Auth::user()->userinfo->latitude . '", "' . Auth::user()->userinfo->longitude . '", "' . Auth::user()->userinfo->city . '", "' . Auth::user()->userinfo->state . '")');

                    if (!is_null($provider)) {
                        $project = Project::create(['categories_id' => \Input::get('category'), 'subcategories_id' => \Input::get('subcategory'), 'user_id' => \Auth::id(), 'title' => \Input::get('project_title'), 'body' => \Input::get('description'), 'range' => \Input::get('range')]);

                        $last_inserted_id = $project->id;

                        foreach ($provider as $select_provider) {

                            ProviderClient::create(['user_id' => \Auth::id(), 'provider_id' => $select_provider->provider_id, 'project_id' => $last_inserted_id]);
                            $count++;
                            \Mail::send('emails.job-notifcation', [\Input::get('project_title'), Input::get('description')], function ($message) use ($select_provider) {
                                $message->to(Provider::find($select_provider->provider_id)->user->email)->subject('Project Recieved');
                                Notification::create([
                                    'text' => 'Received New Job ' . Input::get('project_title'),
                                    'link' => '/projects',
                                    'from' => Auth::user()->id,
                                    'user_id' => Provider::find($select_provider->provider_id)->user_id,
                                ]);
                            });
                        }

                        if ($count > 0) {
                            \Session::flash("success", "Job Sent to " . $count . " Providers");
                        } else {
                            \Session::flash("success", "Job Sent to Provider");
                        }

                        return \Redirect::to('/user');
                    }
                }
            }
        }
    }

    public function hire()
    {
        $project_id = \Input::get('project_id');
        $provider_id = \Input::get('provider_id');

        if (\Auth::user()->hasRole('User')) {
            $project = Project::find($project_id);
            if ($project->provider_id != 'NULL' || $project->provider_id != 0) {
                $project->provider_id = $provider_id;
                $project->save();
                Notification::create([
                    'text' => 'Congratulation you are hired for job - ' . $project->title,
                    'link' => '/profile/' . $provider_id,
                    'from' => Auth::user()->id,
                    'user_id' => $provider_id,
                ]);
                $provider_client = \DB::table('provider_client')->where('project_id', '=', $project_id)->where('provider_id', '<>', $provider_id)->delete();
                return \Redirect::to('/profile');
            } else {
                return \Redirect::to('/profile');
            }
        } else {
            return \Redirect::to('/profile');
        }
    }

    public function endContract()
    {
        if (\Input::get('provider_id') and \Input::get('project_id') and \Input::get('rating')) {
            $project = Project::find(\Input::get('project_id'));
            $pro = Provider::whereId(Input::get('provider_id'))->get()[0];
            $project->ended = 1;
            $project->feedback = \Input::get('rating');
            $project->updated_at = Carbon::now();
            $project->save();
            $provider = Provider::find(\Input::get('provider_id'));
            $provider->rating = \Input::get('rating');
            $provider->save();
            Notification::create([
                'text' => 'Contract Ended',
                'link' => '/profile/' . $pro->id,
                'from' => $project->user_id,
                'user_id' => $pro->user->id,
            ]);
            return "Thanks for providing Feedback";
        } else {
            return "There is some error while providing feedback.";
        }
    }

    public function getRating($id)
    {
        $project = Project::find($id);
        return $project->feedback;
    }

    public function destroy()
    {
        if (Project::find(\Input::get('project_id'))->user_id == \Auth::id()) {
            Project::find(\Input::get('project_id'))->delete();
            \Session::flash("deleted", "Job Deleted Successfully");
            return \Redirect::to('/user');
        } else {
            return false;
        }
    }
}
