<?php namespace App\Console\Commands;

use App\Project;
use App\ProviderClient;
use App\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class SendJobConsole extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'send:job';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        $projects = Project::whereProviderId(null)->wherePremium(1)->where('forward_date', '<=', Carbon::now())->get();

        try {
            foreach ($projects as $project) {

                Project::find($project->id)->update([
                    'premium' => 0,
                    'forward_date' => null
                ]);

                $provider = \DB::select('call GetCatSubcat("' . $project->categories_id . '","' . $project->subcategories_id . '","' . User::find($project->user_id)->userinfo->latitude . '", "' . User::find($project->user_id)->userinfo->longitude . '", "' . User::find($project->user_id)->userinfo->city . '", "' . User::find($project->user_id)->userinfo->state . '")');

                foreach ($provider as $select_provider) {
                    ProviderClient::create(['user_id' => \Auth::id(), 'provider_id' => $select_provider->provider_id, 'project_id' => $provider->id]);
                    \Mail::send('emails.job-notifcation', [\Input::get('project_title'), Input::get('description') ], function ($message) use ($select_provider) {
                        $message->to(Provider::find($select_provider->provider_id)->user->email)->subject('New Project Received');
                        Notification::create([
                            'text' => 'Received New Job ' . Input::get('project_title'),
                            'link' => '/projects',
                            'from' => Auth::user()->id,
                            'user_id' => Provider::find($select_provider->provider_id)->user_id
                        ]);
                    });
                }
            }
        } catch (\Exception $e) {
            echo $e->getMessage();
        }

    }


}
