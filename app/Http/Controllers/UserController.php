<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\App;
use App\Helpers\Parser\Parse;
use App\Localization;
use App\Factorie;
use App\Printer;
use App\Printing;
use App\Continent;
use App\Country;

class UserController extends Controller
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

    public function index($country)
    {
    	

    	$country = Country::where('name','=', $country)
                 ->firstOrFail();
         $getLang = $this->getLang($country);
        $factorie = Factorie::where('country_id',$country['id'])->get(); 
        $post['country'] = $country;
        $post['factorie'] = $factorie;


        return view('pages.user',[
    		'arResult' => $post,
            'allLocal' => $getLang,
    		]); 
    }

     public function info(Request $request,$country)
    {
    	if($request->input('value'))
    	{
    		$value = json_decode($request->input('value'));
    		if(isset($value->user_name) and isset($value->date_before) and isset($value->date_after) and isset($value->region_id))
    		{


    			$printing = Printing::where('user_name',$value->user_name)
    					->where('factorie_id',$value->region_id)
    					->whereDate('created_at', '<=', $value->date_before)
    					->whereDate('created_at', '>=', $value->date_after)
    					->get();
			}
			else if(!isset($value->user_name) and isset($value->date_before) and isset($value->date_after) and isset($value->region_id))
    		{
    			$printing = Printing::where('factorie_id',$value->region_id)
    					->whereDate('created_at', '<=', $value->date_before)
    					->whereDate('created_at', '>=', $value->date_after)
    					->get();
			}
    		else
    		{
    			$post['status'] = '0';
	    		$post['error'] = 'No data available';
	    		echo json_encode($post);
	    		return;
    		}
    			

				if(count($printing) > 0)
				{
					$post['status'] = '1';
					$top = array();
					$printers = array();
					for($i=0;$i<count($printing);$i++)
					{
						//Обновить базу по принтерам
						Parse::parsePrinter($printing[$i]['printer_id']);

						if(!in_array($printing[$i]['printer_id'], $printers))
						{
							$arr = [
									"printer_id" => $printing[$i]['printer_id'],
									"amount" => $printing[$i]['amount'],
								];
							array_push($top, $arr);
							array_push($printers, $printing[$i]['printer_id']);

						}
						else
						{
							$num = array_search($printing[$i]['printer_id'], $printers);
							$sum1 = $top[$num]['amount'];
							$sum2 =  $printing[$i]['amount'];
							$top[$num]['amount'] = $sum1 + $sum2;
						}

						
					}

					$printers_id = array();
					for($i=0;$i<count($top);$i++)
					{
						array_push($printers_id, $top[$i]['printer_id']);
					}

					if(count($printers_id) == 1)
					{

						$printer_info = Printer::where('id',$printers_id)->get();
					}
					else
					{
						
						$printer_info = Printer::whereIn('id',[1,2,3])->get();
					}
					

					for($i=0;$i<count($top);$i++)
					{
						$top1[$i]['printer_name'] = $printer_info[$i]['printer_name'];
						$top1[$i]['amount'] = $top[$i]['amount'];
					}

					if(count($top1) <= 5 && count($top1) > 0)
					{
						$post['top'] = $top1;
						echo json_encode($post);
					}
					else if(count($top1) > 5)
					{
						$arr_top = array();
						for($i=0;$i<count($top1);$i++)
						{
							if($i >= 0 && $i <= 4)
							{
								array_push($arr_top, $top1[$i]);
							}
							else
							{
								if($top1[$i]['amount'] > $arr_top[0]['amount']) 
								{
									$arr_top[0] = $top1[$i];
									continue;
								}
								else if($top1[$i]['amount'] > $arr_top[1]['amount']) 
								{
									$arr_top[1] = $top1[$i];
									continue;
								}
								else if($top1[$i]['amount'] > $arr_top[2]['amount']) 
								{
									$arr_top[2] = $top1[$i];
									continue;
								}
								else if($top1[$i]['amount'] > $arr_top[3]['amount']) 
								{
									$arr_top[3] = $top1[$i];
									continue;
								}
								else if($top1[$i]['amount'] > $arr_top[4]['amount']) 
								{
									$arr_top[4] = $top1[$i];
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
					$post['status'] = '0';
		    		$post['error'] = 'The variable is not correctly set';
		    		echo json_encode($post);
				}
    		
    		
    	}
	}
}
