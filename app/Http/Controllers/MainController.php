<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\App;
use App\Factorie;
use App\Printer;
use App\Printing;
use App\Continent;
use App\Country;
use App\Localization;

class MainController extends Controller
{
    function getLang($country=['lang'=>'ru'])
    {

        $lang = Session::get('local');

        if(empty($lang))
        {
            $lang = 'en';
        }

        $post['active'] = $lang;

        //2 Локализации
        $post['all'][1]['lang'] = 'en';
        $post['all'][0]['lang'] = $country['lang'];
        

        App::setLocale($lang);

        return $post;
    }

    public function continent()
    {
        $post = array();
        $continent = Continent::get();
        foreach ($continent as $key => $value) {
            $country_count = Country::where('continent_id',$value['id'])->count();   
            $arr['continent'] = $value['name'];
            $arr['country_count'] = $country_count;
            array_push($post, $arr);
        }

    	return view('pages.continent',[
    		'arResult' => $post,
    		]);
    }
    public function country($continent)
    {
        
        
    	$continent = Continent::where('name','=', $continent)
                 ->firstOrFail();
        $country = Country::where('continent_id','=', $continent['id'])
                 ->get(); 
        return view('pages.country',[
    		'arResult' => $country,
    		]); 
    }
    public function index($country)
    {
        

    	$country = Country::where('name','=', $country)
                 ->firstOrFail();
         $getLang = $this->getLang($country);
        $factorie = Factorie::where('country_id',$country['id'])->get(); 
        $post['country'] = $country;
        $post['factorie'] = $factorie;


        return view('pages.index',[
    		'arResult' => $post,
            'allLocal' => $getLang,
    		]);  
    }
}
