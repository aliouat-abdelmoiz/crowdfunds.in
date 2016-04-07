<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Provider;
use App\User;
use App\Userinformation;
use Auth;
use Illuminate\Support\Collection;

class TestApiController extends Controller
{
    public function CheckNearByUsers()
    {
        $job = Auth::user()->userinfo;

        \DB::enableQueryLog();

        $cat = \DB::select('call GetCatSubcat("18","17","' . Auth::user()->userinfo->latitude . '", "' . Auth::user()->userinfo->longitude . '", "' . Auth::user()->userinfo->city . '", "' . Auth::user()->userinfo->state . '")');



        dd($cat);

//        if(count($category) > 0) {
//            $provider = \DB::table('providers')->join('userinformation', 'providers.user_id', '=', 'userinformation.user_id')->leftJoin(
//                'provider_cat_subcat', 'providers.id', '=', 'provider_cat_subcat.provider_id'
//            )->whereRaw('GetDistance(\'MI\', userinformation.latitude, userinformation.longitude, ?, ?) < 25', [
//                Auth::user()->userinfo->latitude,
//                Auth::user()->userinfo->longitude
//            ])->orWhereRaw('providers.range = "city" AND userinformation.city = ?', [Auth::user()->userinfo->city])->orWhereRaw(
//                'providers.range = "state" AND userinformation.state = ?', [Auth::user()->userinfo->state]
//            )->get();
//
//            print_r(\DB::getQueryLog());
//
//            dd($provider);
//        } else {
//            echo "no cat";
//        }

    }
}
