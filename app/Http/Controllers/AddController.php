<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Factorie;
use App\Printer;
use App\Continent;
use App\Country;

class AddController extends Controller
{
	public function printer(Request $request)
	{
		if(!empty($request->input('print_factorie')) and !empty($request->input('printer_name')) and !empty($request->input('model')) and !empty($request->input('color')) and !empty($request->input('link')) )
		{
			$printer = new Printer;
			$printer->factorie_id = $request->input('print_factorie');
			$printer->site = $request->input('site');
			$printer->lang = $request->input('lang');
			$printer->printer_name = $request->input('printer_name');
			$printer->model = $request->input('model');
			$printer->color = $request->input('color');
			$printer->link = $request->input('link');
			$printer->location =$request->input('location');
			$printer->notes = $request->input('notes');
			$printer->save();	
			return redirect()->back();
		}
		else
		{
			$post = array();
			$printer_count = Printer::count();
			$factorie = Factorie::get();
			$post['count_printer'] = $printer_count;
			$post['factorie'] = $factorie;

			return view('add.printer',[
	    		'arResult' => $post,
	    		]); 	
		}


		
	}
	public function factorie(Request $request)
	{
		if(!empty($request->input('country_id')) and !empty($request->input('location')))
		{
			$factorie = new Factorie;
			$factorie->country_id = $request->input('country_id');
			$factorie->name = $request->input('name');
			$factorie->location = $request->input('location');
			$factorie->save();
			return redirect()->back();
		}
		else
		{
			$post = array();
			$factorie_count = Factorie::count();
			$country = Country::get();
			$post['count_factorie'] = $factorie_count;
			$post['country'] = $country;
			return view('add.factorie',[
	    		'arResult' => $post,
	    		]); 
		}

		
	}
	public function country(Request $request)
	{
		 if($request->isMethod('post')){

	        if($request->hasFile('flag') and !empty($request->input('name')) and !empty($request->input('lang'))  and !empty($request->input('lang1')) and !empty($request->input('lang2')) and !empty($request->input('continent_id')) ) 
	        {

	            $file = $request->file('flag');
	            $extension = $file->getClientOriginalExtension();
	            $name_file = rand(11111,99999);
	            $file->move(storage_path() . '/app/public/flag',$name_file.'.'.$extension);

	            $path = 'storage/app/public/flag/'.$name_file.'.'.$extension;
	            $lang = array();
	            $lang = [
	            	'en' => ['title' => $request->input('lang1')],
	            	$request->input('lang').'' =>['title' => $request->input('lang2')],
	            ];
	            $lang = json_encode($lang);

	            $country  = new Country;
	            $country->name = $request->input('name');
	            $country->flag = $path;
	            $country->content = $lang;
	            $country->continent_id = $request->input('continent_id');
	            $country->lang = $request->input('lang');
	            $country->save();
	            return redirect()->back();
	        }
	     }

    	else
    	{
	    	$post = array();
			$count_country  = Country::count();
			$continent = Continent::get();
			$post['count_country'] = $count_country;
			$post['continent'] = $continent;
			return view('add.country',[
	    		'arResult' => $post,
	    		]); 	
    	}

       
    		//$file = $request->file('flag');
            //dd($file);
      
	    

		
	}

}