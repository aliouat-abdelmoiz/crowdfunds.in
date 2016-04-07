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

        foreach ($projects as $project) {

            Project::find($project->id)->update([
                'premium' => 0,
                'forward_date' => null
            ]);

            $provider = \DB::select('call GetCatSubcat("' . $project->categories_id . '","' . $project->subcategories_id . '","' . User::find($project->user_id)->userinfo->latitude . '", "' . User::find($project->user_id)->userinfo->longitude . '", "' . User::find($project->user_id)->userinfo->city . '", "' . User::find($project->user_id)->userinfo->state . '")');

//
//            $provider = \DB::select("SELECT providers.id,
//            providers.user_id,
//            providers.range,
//            provider_cat_subcat.category_id,
//            provider_cat_subcat.subcategory_id,
//            provider_cat_subcat.provider_id,
//            userinformation.user_id,
//            userinformation.zip_code,
//            userinformation.latitude,
//            userinformation.longitude
//            FROM userinformation INNER JOIN providers ON userinformation.user_id = providers.user_id
//            INNER JOIN provider_cat_subcat ON provider_cat_subcat.provider_id = providers.id
//            where provider_cat_subcat.category_id LIKE '%" . $project->categories_id . "%' AND provider_cat_subcat.subcategory_id LIKE
//            '%" . $project->subcategories_id . "%' AND inrange('" . User::find($project->user_id)->userinfo->latitude . "','" . User::find($project->user_id)->userinfo->longitude
//                . "', userinformation.latitude, userinformation.longitude, 'Miles') < 25");

            foreach ($provider as $select_provider) {
                echo $select_provider->name . "\n";
            }
        }
    }


}
