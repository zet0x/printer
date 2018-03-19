<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\App;
use App\Localization;
use App\Factorie;
use App\Printer;
use App\Printing;
use App\Continent;
use App\Country;

class ExportController extends Controller
{
	
	function getLang($country)
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

    public function index(Request $request, $country)
    {
    	$country = Country::where('name','=', $country)
                 ->firstOrFail();
         $getLang = $this->getLang($country);
        $factorie = Factorie::where('country_id',$country['id'])->get(); 
        $post['country'] = $country;
        $post['factorie'] = $factorie;

        if($request->input('region_id') && $request->input('date-after') && $request->input('date-before'))
        {
        	$printing = Printing::where('factorie_id',$request->input('region_id'))
    					->whereDate('created_at', '<=', $request->input('date-before'))
    					->whereDate('created_at', '>=', $request->input('date-after'))
    					->get();
    		$printer = Printer::where('factorie_id',$request->input('region_id'))	
    							->get();		

			$printer_arr = array();

			for($i=0;$i<count($printer);$i++)
			{
				$printer_arr[$printer[$i]['id']] = $printer[$i]['printer_name'];
			}

    		$post = array();
    		$users = array();
    		$printers = array();


    		for($i=0;$i<count($printing);$i++)
    		{
    			if(!in_array($printing[$i]['user_name'], $users) or !in_array($printing[$i]['printer_id'], $printers))
    			{

    				$arr = [
							"created_at" => $printing[$i]['created_at'],
							"user_name" => $printing[$i]['user_name'],
							"printer_id" =>  $printer_arr[$printing[$i]['printer_id']],
							"amount" => $printing[$i]['amount'],
						];

					array_push($post, $arr);
					array_push($users, $printing[$i]['user_name']);
					array_push($printers, $printing[$i]['printer_id']);

    			}
    			else if(in_array($printing[$i]['user_name'], $users))
    			{
    				$num = array_search($printing[$i]['user_name'], $users);
					$sum1 = $post[$num]['amount'];
					$sum2 =  $printing[$i]['amount'];
					$post[$num]['amount'] = $sum1 + $sum2;
    			}
    			else if(!in_array($printing[$i]['user_name'], $users))
    			{

    				$num = array_search($printing[$i]['printer_id'], $printers);
					$sum1 = $post[$num]['amount'];
					$sum2 =  $printing[$i]['amount'];
					$post[$num]['amount'] = $sum1 + $sum2;
    			}
    		}
    		
			//dd($post);

			//export csv

			header("Content-type: text/csv");
			header("Content-Disposition: attachment; filename=file.csv");
			header("Pragma: no-cache");
			header("Expires: 0");
			$out = fopen('php://output', 'w');
			
			fputcsv($out,array('Printer name','User name','Amount','Date','Clock'));
			for($i=0;$i<count($post);$i++)
			{
                $arrDate = explode(' ', trim($post[$i]['created_at']));

				fputcsv($out,array($post[$i]['printer_id'],$post[$i]['user_name'],$post[$i]['amount'],$arrDate[0],$arrDate[1]));

			}
			
			
			
			
			fclose($out);
			
			


        }
        else
        {
        	 return view('pages.export',[
	    		'arResult' => $post,
            	'allLocal' => $getLang,
	    		]); 
        }


       
    }
}
