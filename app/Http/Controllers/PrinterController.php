<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\App;
use App\Helpers\Parser\Parse;
use App\Factorie;
use App\Printer;
use App\Printing;
use App\Continent;
use App\Country;


class PrinterController extends Controller
{
	private function getLang($country)
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

    private function printerInfo($id_printer)
    {
    	$printer = Printer::where('id',$id_printer)
    						->firstOrFail();
		return $printer;
    }

    public function index($country)
    {
    	



		$country = Country::where('name','=', $country)
                 ->firstOrFail();
        $getLang = $this->getLang($country);
        $factorie = Factorie::where('country_id',$country['id'])->get(); 
        $post['country'] = $country;
        $post['factorie'] = $factorie;


        return view('pages.printer',[
    		'arResult' => $post,
            'allLocal' => $getLang,
    		]); 
    }

    public function factorie(Request $request)
    {
    	if($request->input('value'))
    	{
    		$post = array();
    		$printer = Printer::where('factorie_id',$request->input('value'))
    					->get();
			for ($i=0; $i < count($printer); $i++) { 
				$post['printer'][$i]['printer_name'] = $printer[$i]['printer_name'];
				$post['printer'][$i]['id'] = $printer[$i]['id'];
			}
			echo json_encode($post);
    	}
    }

    public function info(Request $request,$country)
    {
    	if($request->input('value'))
    	{
    		$value = json_decode($request->input('value'));
    		if(isset($value->printer_id) and isset($value->date_before) and isset($value->date_after) and isset($value->region_id) and $value->region_id != 'none')
    		{

    			//Обновить базу по принтерам
    			Parse::parsePrinter($value->printer_id);

    			$printing = Printing::where('printer_id',$value->printer_id)
	    					->where('factorie_id',$value->region_id)
	    					->whereDate('created_at', '<=', $value->date_before)
	    					->whereDate('created_at', '>=', $value->date_after)
	    					->get();

    		}
    		else if(!isset($value->printer_id) and isset($value->date_before) and isset($value->date_after) and isset($value->region_id))
    		{

    			$printing = Printing::where('factorie_id',$value->region_id)
    						->whereDate('created_at', '<=', $value->date_before)
	    					->whereDate('created_at', '>=', $value->date_after)
	    					->get();

				
    		}
    		else if($value->region_id == 'none')
    		{
    			$post['status'] = '0';
	    		$post['error'] = 'Choose region';
	    		return json_encode($post);
    		}
    		
			if(count($printing) > 0)
			{
				if(isset($value->printer_id))
				{
					$printer_info = $this->printerInfo($value->printer_id);
					$post['printer_info'] = $printer_info;	
				}
				$post['status'] = '1';
				$top = array();
				$users = array();
				for($i=0;$i<count($printing);$i++)
				{
					if(!in_array($printing[$i]['user_name'], $users))
					{
						$arr = [
								"user_name" => $printing[$i]['user_name'],
								"amount" => $printing[$i]['amount'],
							];
						array_push($top, $arr);
						array_push($users, $printing[$i]['user_name']);

					}
					else
					{
						$num = array_search($printing[$i]['user_name'], $users);
						$sum1 = $top[$num]['amount'];
						$sum2 =  $printing[$i]['amount'];
						$top[$num]['amount'] = $sum1 + $sum2;
					}

					
				}

				if(count($top) <= 5 && count($top) > 0)
				{
					$post['top'] = $top;
					echo json_encode($post);
				}
				else if(count($top) > 5)
				{
					$arr_top = array();
					for($i=0;$i<count($top);$i++)
					{
						if($i >= 0 && $i <= 4)
						{
							array_push($arr_top, $top[$i]);
						}
						else
						{
							if($top[$i]['amount'] > $arr_top[0]['amount']) 
							{
								$arr_top[0] = $top[$i];
								continue;
							}
							else if($top[$i]['amount'] > $arr_top[1]['amount']) 
							{
								$arr_top[1] = $top[$i];
								continue;
							}
							else if($top[$i]['amount'] > $arr_top[2]['amount']) 
							{
								$arr_top[2] = $top[$i];
								continue;
							}
							else if($top[$i]['amount'] > $arr_top[3]['amount']) 
							{
								$arr_top[3] = $top[$i];
								continue;
							}
							else if($top[$i]['amount'] > $arr_top[4]['amount']) 
							{
								$arr_top[4] = $top[$i];
								continue;
							}
							
						}
					}
					$post['top'] = $arr_top;
					echo json_encode($post);
				}	
			}
			else
			{
				if(isset($value->printer_id))
				{
					$printer_info = $this->printerInfo($value->printer_id);
					$post['printer_info'] = $printer_info;	
				}
    			$post['status'] = '0';
	    		$post['error'] = 'No data available';
	    		echo json_encode($post);
			}
    	}
    	else
    	{
    		$post['status'] = '0';
    		$post['error'] = 'The variable is not correctly set';
    		echo json_encode($post);
    	}
    	
    }
}
