<?php

namespace App\Http\Controllers;

use App\Category;
use App\Page;
use App\Http\Requests;
use DB;
use Symfony\Component\Config\Definition\Exception\Exception;

class PageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function page($name)
    {
        try {
            $page = Page::whereName($name)->get();
            return view('Pages.index', compact('page'));
        } catch (\Exception $e) {
            return \Redirect::to('/');
        }
    }

    public function country($country)
    {
        if ($country != '') {
            $result = DB::table('provider_cat_subcat')->join('providers', 'providers.id', '=', 'provider_cat_subcat.provider_id')->join('userinformation', 'userinformation.user_id', '=', 'providers.user_id')->select([
                'provider_cat_subcat.category_id', 'providers.user_id', 'userinformation.city', 'userinformation.state', 'userinformation.country'
            ])->where('userinformation.country', '=', $country)->groupBy('provider_cat_subcat.category_id')->get();

            $data = [];

            foreach ($result as $item) {
                $category = unserialize(json_decode($item->category_id));
                for ($i = 0; $i < count($category); $i++) {
                    $data [] = "
                    <a href='/Items/" . \App\Category::find($category[$i])->name . "/$category[$i]/'>" . \App\Category::find($category[$i])->name . "</a><br>
                    <small style='color: #ccc;'>" . Category::find($category[$i])->content . "</small>";
                }
            }
            sort($data);
            return view('seo.index', compact('data', 'country'));
        }
    }

    public function state($country, $state)
    {
        if ($country != '' and $state != '') {
            $result = DB::table('provider_cat_subcat')->join('providers', 'providers.id', '=', 'provider_cat_subcat.provider_id')->join('userinformation', 'userinformation.user_id', '=', 'providers.user_id')->select([
                'provider_cat_subcat.category_id', 'providers.user_id', 'userinformation.city', 'userinformation.state', 'userinformation.country'
            ])->where('userinformation.country', '=', $country)->where('userinformation.state', '=', $state)->groupBy('provider_cat_subcat.category_id')->get();
            $data = [];
            foreach ($result as $item) {
                $category = unserialize(json_decode($item->category_id));
                for ($i = 0; $i < count($category); $i++) {
                    $data [] = "
                    <a href='/Items/" . \App\Category::find($category[$i])->name . "/$category[$i]/'>" . \App\Category::find($category[$i])->name . "</a><br>
                    <small style='color: #ccc;'>" . Category::find($category[$i])->content . "</small>";
                }
            }
            sort($data);
            return view('seo.index', compact('data', 'state', 'country'));
        }
    }

    public function city($country, $state, $city)
    {
        if ($country != '' and $state != '' and $city != '') {
            $result = DB::table('provider_cat_subcat')->join('providers', 'providers.id', '=', 'provider_cat_subcat.provider_id')->join('userinformation', 'userinformation.user_id', '=', 'providers.user_id')->select([
                'provider_cat_subcat.category_id', 'providers.user_id', 'userinformation.city', 'userinformation.state', 'userinformation.country'
            ])->where('userinformation.country', '=', $country)->where('userinformation.state', '=', $state)->where('userinformation.city', '=', $city)->groupBy('provider_cat_subcat.category_id')->get();
            $data = [];
            foreach ($result as $item) {
                $category = unserialize(json_decode($item->category_id));
                for ($i = 0; $i < count($category); $i++) {
                    $data [] = "
                    <a href='/Items/" . \App\Category::find($category[$i])->name . "/$category[$i]/'>" . \App\Category::find($category[$i])->name . "</a><br>
                    <small style='color: #ccc;'>" . Category::find($category[$i])->content . "</small>";
                }
            }
            sort($data);
            return view('seo.index', compact('data', 'state', 'country', 'city'));
        }
    }
}
