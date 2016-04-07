<?php namespace App\Http\Controllers;

use App\Category;
use App\Http\Requests;
use App\Project;
use App\Provider;
use App\ProviderPivot;
use App\Subcategory;
use App\User;
use App\Userinformation;
use Auth;
use Illuminate\Http\Request;
use Image;

class UserController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        if (Auth::guest()) {
            echo "hi";
        } elseif (!User::find(Auth::id())->hasRole('Provider')) {
            $profile = User::find(Auth::id());
            $user_projects = Project::whereUserId(Auth::id())->get();
            $conversations = User::find(Auth::id())->conversations;
            $names = [];
            foreach ($conversations as $conversation) {
                $message = $conversation->messages;
            }
            $plans = [];
            return view('User.index', compact('profile', 'user_projects', 'conversations', 'message', 'names', 'plans'));
        } else {
            $profile = User::find(Auth::id());
            $category = Category::all();

            $user_projects = Project::whereUserId(Auth::id())->get();
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
            provider_client.project_id
        FROM provider_client INNER JOIN projects ON provider_client.project_id = projects.id where provider_client.provider_id = ? AND projects.ended = 0", [\Auth::user()->provider->id]);
            $category_subcategories = ProviderPivot::whereProviderId(\Auth::user()->provider->id)->get();
            $catsubcats = [];
            foreach ($category_subcategories as $catsubcat) {
                $catsubcats ['cat'][] = $catsubcat->category_id;
                $catsubcats ['subcat'][] = $catsubcat->subcategory_id;
            }

            if(count($catsubcats) > 0) {
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


                $select_category_name = Category::whereRaw('id IN(' . $implode_cat_id . ')')->get(['name']);
                $select_subcategory_name = Subcategory::whereRaw('id IN(' . $implode_subcat_id . ')')->get(['name']);

                $hired = Provider::with("projects")->whereId(Auth::user()->provider->id)->get();

                $conversations = User::find(Auth::id())->conversations;
                if (Auth::user()->stripe_active) {
                    $plans = Auth::user()->plans;
                    return view('User.index', compact('hired', 'user_projects', 'conversations'))->with('profile', $profile)->with('projects', $projects)->with('categories', $select_category_name)->with('subcategories', $select_subcategory_name)->with('plans', $plans);
                } else {
                    $plans = [];
                    return view('User.index', compact('hired', 'user_projects', 'conversations'))->with('profile', $profile)->with('projects', $projects)->with('categories', $select_category_name)->with('subcategories', $select_subcategory_name)->with('plans', $plans);
                }
            } else {
                $hired = Provider::with("projects")->whereId(Auth::user()->provider->id)->get();

                $conversations = User::find(Auth::id())->conversations;
                if (Auth::user()->stripe_active) {
                    $plans = Auth::user()->plans;
                    return view('User.index', compact('hired', 'user_projects', 'conversations'))->with('profile', $profile)->with('projects', $projects)->with('plans', $plans);
                } else {
                    $plans = [];
                    return view('User.index', compact('hired', 'user_projects', 'conversations'))->with('profile', $profile)->with('projects', $projects)->with('plans', $plans);
                }
            }
        }
    }

    public function edit($id)
    {
        $user = Userinformation::find(Auth::user()->userinfo->id);
        return view('User.edit', compact('user'));
    }

    public function update($id)
    {
        $data = \Request::all();

        $image = "";

        if(\Input::hasFile('avatar')) {
            $file = \Input::file('avatar');
            $image =  md5(time() . \Input::file('avatar')->getClientOriginalName()) . "." . \Input::file('avatar')->getClientOriginalExtension();
            $file->move(public_path('uploads/users/logos'), $image);
            $avatar = Image::make(sprintf('uploads/users/logos/%s', $image))->resize(200, 200)->save();
        }

        $userinfo = Userinformation::find($id);
        $userinfo->update($data);
        Auth::user()->update([
            'name' => \Input::get('name'),
            'avatar' => $image
        ]);
        return \Redirect::route('user.index');
    }

}
