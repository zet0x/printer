<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use App\Country;

class LocalController extends Controller
{
	public function index($lang)
	{
		if($lang == 'en')
        {
           $lang = 'en';
        }
        else
        {
            $country = Country::where('lang','=', $lang)
                     ->firstOrFail();

            if(empty($country))
            {
                $lang = 'en';
            }
            else
            {
                $lang = $country['lang'];
            }   
        }
        


        Session::put('local', $lang);
        return Redirect::back();
	}  
}
