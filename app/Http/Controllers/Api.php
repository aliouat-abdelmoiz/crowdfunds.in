<?php
namespace App\Http\Controllers;

use App;
use App\Adv_Management;
use App\Category;
use App\Conversation;
use App\Http\Requests;
use App\Message;
use App\Notification;
use App\Plan;
use App\Project;
use App\Provider;
use App\Subcategory;
use App\User;
use Carbon\Carbon;
use Elasticsearch\ClientBuilder;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Symfony\Component\Console\Helper\Table;

use URL;
use Illuminate\Session\Store;
use Clockwork\Support\Laravel\ClockworkMiddleware;

class Api extends Controller
{
    function __construct()
    {
        $user = new User();
        \ZipCode::setCountry('US');
    }

    public function PostFile()
    {
        dd(\Input::file('file'));
    }

    public function ShowAdvertise()
    {
        \ZipCode::setCountry('US');
        $zip = file_get_contents('http://ip-api.com/json');
        $info = json_decode($zip);
        if (\Auth::guest()) {
            $advertise = \DB::select("
                SELECT plans.id,
                plans.active,
                adv__managements.id,
                adv__managements.plan_id,
                adv__managements.categories,
                adv__managements.subcategories,
                adv__managements.images,
                adv__managements.title,
                adv__managements.description,
                userinformation.id,
                userinformation.latitude,
                userinformation.longitude,
                userinformation.country,
                userinformation.city,
                userinformation.state,
                userinformation.user_id
            FROM adv__managements INNER JOIN plans ON adv__managements.plan_id = plans.id
                 INNER JOIN userinformation ON plans.user_id = userinformation.user_id
            WHERE (active = 1 AND GetDistance('MI', userinformation.latitude, userinformation.longitude, '" . $info->lat . "', '" . $info->lon . "') <= 25)
            ORDER BY RAND() LIMIT 3
            ");
            return \Response::make(['advertise' => $advertise]);
        } else {
            $advertise = \DB::select("
                SELECT plans.id,
                plans.active,
                plans.user_id AS 'plid',
                adv__managements.id,
                adv__managements.plan_id,
                adv__managements.categories,
                adv__managements.subcategories,
                adv__managements.images,
                adv__managements.title,
                adv__managements.description,
                userinformation.id,
                userinformation.latitude,
                userinformation.longitude,
                userinformation.country,
                userinformation.city,
                userinformation.state,
                userinformation.user_id
            FROM adv__managements INNER JOIN plans ON adv__managements.plan_id = plans.id
                 INNER JOIN userinformation ON plans.user_id = userinformation.user_id
            WHERE (active = 1 AND GetDistance('MI', userinformation.latitude, userinformation.longitude, '" . \Auth::user()->userinfo->latitude . "', '" . \Auth::user()->userinfo->longitude . "') <= 25)
            AND plans.user_id <> '" . \Auth::id() . "'
            ORDER BY RAND() LIMIT 3
            ");
            return \Response::make(['advertise' => $advertise]);
        }
    }

    public function MakeImppression()
    {
        $adv = Adv_Management::find(\Input::get('id'));
        if (Plan::find($adv->plan_id)->balance == 0.0 || 0) {
            Plan::find($adv->plan_id)->update(['paid' => 0, 'active' => 0]);
        } else {
            $adv->total_impression += 4;
            $adv->save();
        }
    }

    public function MakeClick()
    {
        $adv = Adv_Management::find(\Input::get('id'));
        $plan = Plan::find($adv->plan_id);
        if ($plan->balance == 0.0 || $plan->balance == 0) {
            $plan->active = 0;
            $plan->paid = 0;
            $plan->save();
        } else {
            if ($plan->balance <= 20) {
                $plan->active = 0;
                $plan->paid = 0;
                $plan->balance = 0;
            } else {
                $plan->balance -= 20.0;
            }
            $adv->total_click += 1;
            $adv->save();
            $plan->save();
        }
    }

    public function getLocationByZip()
    {
        $zip = \Input::get('zipcode');
        $location = \ZipCode::find($zip);
        return $location['addresses'];
    }

    public function getsubcats()
    {
        if (\Input::get('category_id') != null) {
            $subcats = Subcategory::whereCategoryId(\Input::get('category_id'))->get(['id', 'name']);
            $data = [];
            foreach ($subcats as $subcat) {
                $data[] = $subcat;
            }
            return json_encode($data);
        } else {
            return "No Category Found";
        }
    }

    public static function checkByMiles($miles)
    {
        if ($miles > 15.4343) {
            return true;
        } else {
            return false;
        }
    }

    public function getUserNameById($id)
    {
        $user = User::find($id);

        if (\Request::ajax()) {
            if (empty($user) || $user == null) {
                return 'User Not Found';
            } else {
                return $user->name;
            }
        }
    }

    public function getProjectsById()
    {
        $projects = \DB::select('SELECT projects.id,
            projects.categories_id,
            projects.subcategories_id,
            projects.user_id,
            projects.provider_id,
            projects.title,
            projects.body,
            projects.image,
            provider_client.id AS "client_id",
            provider_client.user_id AS "puser_id",
            provider_client.provider_id AS "pprovider_id",
            provider_client.project_id AS "pproject_id"
        FROM provider_client INNER JOIN projects ON provider_client.project_id = projects.id WHERE provider_client.provider_id = ?',
            [\Input::get('id')]);

        $result = [];
        foreach ($projects as $project) {
            $result[] = $project;
        }

        return json_encode($result);
    }

    public function hireUserForProject()
    {
        if (\Auth::check()) {
            $project_id = \Input::get('hire_project_id');
            $provider_id = \Input::get('hire_provider_id');

            $project = Project::find($project_id);

            $pro = Provider::whereId($provider_id)->get()[0];

            $user_email = $pro->user->email;

            $project->provider_id = $provider_id;
            $project->save();

            $provider_client = \DB::table('provider_client')->where('project_id', '=', $project_id);

            foreach ($provider_client as $pc) {
                \DB::table('provider_client')->where('project_id', '=', $project_id)->where('provider_id', '<>',
                    $provider_id)->delete();
            }

            \Mail::send('emails.hire', ['project' => $project->title, 'user' => \Auth::user()->name],
                function ($message) use ($project_id, $user_email, $pro, $project) {
                    $message->replyTo(\Auth::user()->email, 'Reply Me');
                    $message->to($user_email)->subject('You hired by ' . \Auth::user()->email);

                    Notification::create([
                        'text' => 'Congratulation you are hired for job - ' . $project->title,
                        'link' => '/profile/' . $pro->id,
                        'from' => \Auth::id(),
                        'user_id' => $pro->user_id
                    ]);
                });

            return "You just hired : " . $pro->name . ", If you want to cancel project please check job posting.";
        }
    }

    public function sendMessage()
    {
        $select_can_send_message_users = \DB::table('provider_client')->where('provider_id', '=',
            \Auth::user()->provider->id)->where('project_id', '=', \Input::get('project_id'))->get();

        if (count($select_can_send_message_users) == 1) {
            $rules = ['subject' => 'required', 'content' => 'required', 'conversation_id' => 'required'];

            $validator = \Validator::make([
                'subject' => \Input::get('subject'),
                'content' => \Input::get('content'),
                'conversation_id' => \Input::get('conversation_id')
            ], $rules);

            if ($validator->fails()) {
                return $validator->errors();
            } else {
                $conversation = Conversation::create([
                    'subject' => \Input::get('subject'),
                    'user_id' => \Auth::id(),
                    'project_id' => \Input::get('project_id'),
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ]);

                $conversation->messages()->create(['user_id' => \Auth::id(), 'content' => \Input::get('content')]);

                $conversation->user()->attach([\Auth::id(), \Input::get('conversation_id')]);

                if (\Auth::user()->hasRole('Provider')) {
                    $project = \DB::table('provider_client')->where('project_id', '=',
                        \Input::get('project_id'))->where('provider_id', '=', \Auth::user()->provider->id)->update([
                        'applied' => 1,
                        'updated_at' => Carbon::now()->toDateTimeString()
                    ]);
                    $current_project = Project::find(Input::get('project_id'));
                    if ($current_project->premium == 1) {
                        $current_project->forward_date = null;
                        $current_project->save();
                    }

                    \Mail::send('emails.job-notifcation',
                        [Project::find(Input::get('project_id'))->title, Input::get('content')],
                        function ($message) {
                            $message->to(User::find(Project::find(Input::get('project_id'))->user_id)->email)->subject('Thank you for applying');
                            Notification::create([
                                'text' => \Auth::user()->email . ' Applied for job ' . Project::find(Input::get('project_id'))->title,
                                'link' => '/jobs/show/' . Input::get('project_id'),
                                'from' => \Auth::user()->id,
                                'user_id' => Project::find(Input::get('project_id'))->user_id
                            ]);
                        });
                }
                return "Message Sent Successfully to " . User::find(\Input::get('conversation_id'))->name . ", Project ID: " . \Input::get('project_id');
            }
        } else {
            return "Sorry you can't send message - maybe you not applied for job or job awarded to someone else";
        }
    }

    public function replyMessage()
    {
        $select_project = Conversation::find(\Input::get('conversation_id'), ['project_id']);

        if (\Auth::user()->hasRole('User') and \Auth::user()->hasRole('Provider')) {
            $select_can_send_message_users = \DB::table('provider_client')->where('provider_id', '=',
                \Auth::user()->provider->id)->where('project_id', '=', $select_project->project_id)->orWhere('user_id',
                '=', \Auth::id())->get();
        } elseif (\Auth::user()->hasRole('User')) {
            $select_can_send_message_users = \DB::table('provider_client')->where('user_id', '=', \Auth::id())->get();
        } else {
            return "There is some error while sending message";
        }

        if (count($select_can_send_message_users) == 0) {
            return "Sorry you can't send message" . \Input::get('conversation_id');
        } else {
            $message = new Message();
            $message->content = \Input::get('content');
            $message->user_id = \Auth::id();
            $message->conversation_id = \Input::get('conversation_id');

            $conversation = Conversation::find(\Input::get('conversation_id'));
            $conversation->updated_at = Carbon::now();
            $conversation->save();

            $table = \DB::select('SELECT total_messages FROM conversation_user WHERE conversation_id=? AND user_id <> ? LIMIT 1',
                [\Input::get('conversation_id'), \Auth::id()]);
            $count = $table[0]->total_messages + 1;

            \DB::table('conversation_user')->where('user_id', '<>', \Auth::id())->where('conversation_id', '=',
                \Input::get('conversation_id'))->update(['total_messages' => $count, 'opened' => 0]);

            $user = \DB::table('conversation_user')->where('user_id', '<>', \Auth::id())->where('conversation_id', '=',
                \Input::get('conversation_id'))->get();

            $username = User::whereId($user[0]->user_id)->get();


            if ($message->save()) {
                \Mail::send('emails.new-message', ['content' => \Input::get('content'), 'user' => $username[0]->name],
                    function ($message) use ($username) {
                        $message->replyTo(\Auth::user()->email, 'Reply Me');
                        $message->to($username[0]->email)->subject('New Message from ' . \Auth::user()->email);
                    });
            } else {
                return "Error sending message";
            }
        }
    }

    public function updateProjectWhenUserHired()
    {
        return "";
    }

    public function getVerify($password = null)
    {
        if (\Auth::check()) {
            $data = User::find(\Auth::id());
            if ($data->confirm_code == '' || $data->activate == 0) {
                $data->confirm_code = bcrypt($data->username) . rand(0, 6000) . 'yourserv';
                $data->save();
            } else {
                return \Redirect::to('/');
            }
            if (isset($password)) {
                \Mail::send('emails.guest_verify',
                    ['confirm' => $data->confirm_code, 'password' => $password, 'link' => env('link-webpage')],
                    function ($message) {
                        $message->to(\Auth::user()->email)->subject('Verify Account');
                    });
            } else {
                \Mail::send('emails.verify', ['confirm' => $data->confirm_code, 'link' => env('link-webpage')],
                    function ($message) {
                        $message->to(\Auth::user()->email)->subject('Verify Account');
                    });
            }
        } else {
            return \Redirect::to('/auth/register');
        }
        return \Redirect::to('/');
    }

    public function getConfirm(Request $request)
    {
        if ($request->get('code') == '' || $request->get('code') == null) {
            echo 'Sorry we can not found any code';
        } else {
            $user = User::whereConfirmCode($request->get('code'))->get(['id', 'username', 'activate']);
            foreach ($user as $u) {
                $data = User::find($u->id);
                if ($data->activate == 1) {
                    return redirect('/auth/login');
                } else {
                    $data->activate = 1;
                    $data->save();
                }
            }
        }
        return \Redirect::to('/profile')->with('status', 'Thanks - We have confirmed your account');
    }

    public function ExtractUsername($email)
    {
        $username = explode("@", $email);
        return $username[0];
    }

    public function getMessageCount()
    {
        $count = \DB::table('conversation_user')->where('user_id', '=', \Auth::id())->where('opened', '=',
            '0')->sum('total_messages');
        $notifcation = \DB::table('Notifications')->where('user_id', '=', \Auth::id())->where('read', '=',
            '0')->count();
        return \Response::make(['messages' => $count, 'notification' => $notifcation], 200);
    }

    public function MarkMessageAsRead()
    {
        try {
            \DB::table('conversation_user')->where('user_id', '=', \Input::get('user_id'))->where('conversation_id',
                '=', \Input::get('conversation_id'))->update(['total_messages' => 0]);
            \DB::table('messages')->where('user_id', '=', \Input::get('user_id'))->where('conversation_id', '=',
                \Input::get('conversation_id'))->update(['opened' => 1]);
        } catch (\PDOException $e) {
            return "404";
        }
    }

    public function MarkMessageAsUnread()
    {
        try {
            \DB::table('conversation_user')->where('user_id', '=', \Input::get('user_id'))->where('conversation_id',
                '=', \Input::get('conversation_id'))->update(['opened' => 0]);
        } catch (\PDOException $e) {
            return "404";
        }
    }

    public function messageNotifcation()
    {
        header("Content-Type: text/event-stream");
        print_r(User::all());
        flush();
    }

    public function decode()
    {
        $cats = json_decode(\Input::get('cat_value'));
        $subcats = json_decode(unserialize(\Input::get('sub_cats')));
        return \Response::make(["cat" => $cats, "subcats" => $subcats]);
    }

    public function suggest()
    {
        $projects = Project::whereProviderId(null)->wherePremium(1)->where('forward_date', '<=', Carbon::now())->get();

        foreach ($projects as $project) {
            $provider = \DB::select('call GetCatSubcat("' . $project->categories_id . '","' . $project->subcategories_id . '","' . User::find($project->user_id)->userinfo->latitude . '", "' . User::find($project->user_id)->userinfo->longitude . '", "' . User::find($project->user_id)->userinfo->city . '", "' . User::find($project->user_id)->userinfo->state . '")');
            foreach ($provider as $select_provider) {
                echo $select_provider->name;
            }
        }
    }

    public function search()
    {
        $client = ClientBuilder::create()->build();

        $params = [
            "body" => [
                "query" => [
                    "bool" => [
                        "should" => [
                            "match" => [
                                "_all" => [
                                    "query" => \Request::input('query'),
                                    "operator" => "or"
                                ]
                            ]
                        ]
                    ]
                ],
                "size" => 5,
                "_source" => [
                    "name",
                    "category"
                ]
            ]
        ];

        $result = $client->search($params);
        return \Response::make($result);
    }

    public function getPremiumUserImpression()
    {
        $providers = User::with([
            'provider' => function ($query) {
                $query->join('provider_cat_subcat', 'providers.id', '=', 'provider_cat_subcat.provider_id');
            }
        ])->where('stripe_active', '=', '1')->get();
        $images = ['test.png'];
        $subcategories_id = [];
        foreach ($providers as $provider) {
            if ($provider->provider->logo == null || empty($provider->provider->logo || $provider->provider == null)) {
                $images[] = Category::all()->get('image');
            } else {
                $images[] = $provider->provider->logo;
            }
            $subcategories_id[] = unserialize(json_decode($provider->provider->subcategory_id));
        }
        return \Response::make(['images' => $images, 'subcategories' => $subcategories_id]);
    }

    public function stopAdvertise()
    {
        if (Input::get('activate') == 1) {
            Plan::whereId(Input::get("id"))->where('user_id', '=', \Auth::id())->update(['active' => 0]);
        } else {
            Plan::whereId(Input::get("id"))->where('user_id', '=', \Auth::id())->update(['active' => 1]);
        }
        return Input::get('activate');
    }

    public function makereadnotify()
    {
        \Auth::user()->Notifications()->update([
            'read' => 1
        ]);
    }

    public function GetCategoryName($id)
    {
        return Category::find($id)->name;
    }

    public function GetSubcategoryName($id)
    {
        return Subcategory::find($id)->name;
    }

    public function GetImpressions()
    {
        $impressions = \DB::table('users')->join('userinformation', 'users.id', '=', 'userinformation.user_id')->join(
            'providers', 'users.id', '=', 'providers.user_id'
        )->join(
            'plans', 'plans.user_id', '=', 'users.id'
        )->join('adv__managements', 'plans.id', '=',
            'adv__managements.plan_id')->whereRaw('GetDistance("MI",userinformation.latitude,userinformation.longitude,?,?) < 25',
            [
                Auth::user()->userinfo->latitude,
                Auth::user()->userinfo->longitude
            ])->where('plans.active', '=', '1')->select([
            'plans.id',
            'userinformation.latitude',
            'userinformation.longitude',
            'userinformation.user_id as uuid',
            'providers.logo',
            'adv__managements.images as adv_images',
            'adv__managements.categories',
            'adv__managements.subcategories',
            'adv__managements.title'
        ])->orderByRaw('RAND()')->limit(30)->get();

        foreach ($impressions as $impression) {
            preg_match_all('#\[\\"([0-9]+)\\"\]#', $impression->categories, $matches); // I chose # as delimiter
            // here – with so many \ involved, we don’t need / around it to add to the confusion

            $results['id'] = $matches[1]; // $matches will be overwritten in each iteration, so we
            // preserve its content here by putting it into the $results array
        }

        return $results['id'];
    }

    public function GetToken($key)
    {
        $key_result = \Crypt::decrypt($key);
        if ($key_result == "d1bd22738593a769d91cee02da581baa" and $key != "") {
            return csrf_token();
        }
    }

    public function subscribe()
    {
        $user = \Auth::user()->id;
        if (\Request::get('_id') == null || \Request::get('_id') == "") {
            $subscribe = \DB::table('subscribe')->insert([
                'subscribed' => 1,
                'user_id' => $user,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]);
            if ($subscribe) {
                return "Success";
            }
        } else {
            $subscribe = \DB::table('subscribe')->update([
                'subscribed' => 1,
                'user_id' => \Request::get('_id'),
                'updated_at' => Carbon::now()
            ]);
            if ($subscribe) {
                return "Success";
            }
        }
    }

    public static function GetPages()
    {
        $pages = \DB::table('pages')->get();
        foreach ($pages as $page) {
            echo "<li><a href='/page/$page->name'>" . $page->name . "</a></li>";
        }
    }

    public function unsubscribe()
    {
        $user = \Auth::user()->id;
        $unsubscribe = \DB::table('subscribe')->where('user_id', '=', $user)->update([
            'subscribed' => 0
        ]);
        if ($unsubscribe) {
            return "Success";
        }
    }

    public function sitemap()
    {
        // create sitemap
        $sitemap_subcategories = App::make("sitemap");

        // add items
        $subcategories = DB::table('subcategories')->orderBy('created_at', 'desc')->get();
        foreach ($subcategories as $item) {
            $sitemap_subcategories->add(htmlentities("http://yourserviceconnection.com/jobs/" . \App\Category::find($item->category_id)->name . "/" . $item->name . "/" . $item->category_id . "/" . $item->id,
                HTML_ENTITIES, 'UTF-8'), \Carbon\Carbon::now(), 1, 'weekly');
        }

        // create file sitemap-posts.xml in your public folder (format, filename)
        $sitemap_subcategories->store('xml', 'sitemap-subcategories');

        // create sitemap
        $sitemap_state = App::make("sitemap");

        // create sitemap
        $sitemap_country = App::make("sitemap");

        // add items
        $posts = DB::table('userinformation')->orderBy('created_at', 'desc')->groupBy('country')->get();
        foreach ($posts as $post) {
            $sitemap_country->add("http://yourserviceconnection.com/page/jobs/" . $post->country, \Carbon\Carbon::now(),
                1, 'weekly');
        }

        // create file sitemap-posts.xml in your public folder (format, filename)
        $sitemap_country->store('xml', 'sitemap-country');

        // create sitemap
        $sitemap_state = App::make("sitemap");

        // add items
        $tags = DB::table('userinformation')->groupBy('state')->get();

        foreach ($tags as $tag) {
            $sitemap_state->add("http://yourserviceconnection.com/page/jobs/" . $tag->country . "/" . $tag->state,
                \Carbon\Carbon::now(), '0.5', 'weekly');
        }

        // create file sitemap-tags.xml in your public folder (format, filename)
        $sitemap_state->store('xml', 'sitemap-state');

        // create sitemap
        $sitemap_city = App::make("sitemap");

        // add items
        $city = DB::table('userinformation')->groupBy('city')->get();

        foreach ($city as $value) {
            $sitemap_city->add("http://yourserviceconnection.com/page/jobs/" . $value->country . "/" . $value->state . "/" . $value->city,
                \Carbon\Carbon::now(), '0.5', 'weekly');
        }

        // create file sitemap-tags.xml in your public folder (format, filename)
        $sitemap_city->store('xml', 'sitemap-city');

        // create sitemap index
        $sitemap = App::make("sitemap");

        // add sitemaps (loc, lastmod (optional))
        $sitemap->addSitemap(URL::to('sitemap-country.xml'));
        $sitemap->addSitemap(URL::to('sitemap-state.xml'));
        $sitemap->addSitemap(URL::to('sitemap-subcategories.xml'));
        $sitemap->addSitemap(URL::to('sitemap-city.xml'));

        // create file sitemap.xml in your public folder (format, filename)
        $sitemap->store('sitemapindex', 'sitemap');
    }

    public function eventStream()
    {
        header('Content-Type: text/event-stream');
        header('Cache-Control: no-cache');
        $count = \DB::table('conversation_user')->where('user_id', '=', \Auth::id())->where('opened', '=',
            '0')->sum('total_messages');
        echo "data: $count \n\n";
        flush();
    }

    public function notificationStream()
    {
        header('Content-Type: text/event-stream');
        header('Cache-Control: no-cache');
        $notifcation = \DB::table('notifications')->where('user_id', '=', \Auth::id())->where('read', '=',
            '0')->count();
        echo "data: $notifcation \n\n";
        flush();
    }
    
}
