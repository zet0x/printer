<?php

namespace App\Helpers\Parser;
use Illuminate\Http\Request;
use App\Printing;
use App\Printer;
use Carbon\Carbon;
//use Illuminate\Support\Facades\DB;
 
class Parse {
    /**
     * @param int $printer_id 
     * 
     * @return boolean
     */
    public static function parsePrinter($printer_id)
	{
		
		$printer = Printer::where('id',$printer_id)
							->firstOrFail();

		$factorie_id = $printer['factorie_id'];

		$link  = $printer['link'];
		

		if (@fopen($link, "r")){
			$content = file($link);
		} else {
			$post['status'] = '0';
			$post['printer_name'] = $printer['printer_name'];
			$post['error'] = 'Not file';
			return json_encode($post);
		}
		
		$arr = array();
		//start parser
		foreach ($content as $key => $value) 
		{
				if($key != 0 and $key != 1)
				{
					$number = '';
					$event['create_at'] = '';

					$value= preg_replace('/\s{2,}/', ' ', $value);
					$value = trim($value);
					$value = explode(' ', $value);
					$event['id'] =$value[0];
					
					
					for($i=0;$i<count($value);$i++)
					{
						if($value[$i] == 'Finished')
						{
							$number = $i;
						}
					}
					for ($i=0; $i < count($value); $i++) { 
						if($number - 1 == $i)
						{
							$event['amount'] = $value[$i];
						} 
						else if($i > $number)
						{
							$event['create_at'] = $event['create_at'].' '.$value[$i];
						}
					}
					if($value[$number - 2] != $event['id'])
					{
						$event['name'] = $value[$number - 2];
					}
					else
					{
						$event['name'] = 'printed on device';
					}
					$event['create_at'] = trim($event['create_at']);


					//$event['create_at'] = $this->dateReplace($event['create_at']);

					$arr_date = explode(' ', $event['create_at']);
					if(count($arr_date)>1)
					{
						$date = explode('/', $arr_date[0]);
						$date_str = '20'.$date[0].'-'.$date[1].'-'.$date[2].' '.$arr_date[1];
					}
					else
					{
						$date_str = date("Y-m-d H:i");
					}
					$date_str = Carbon::createFromFormat('Y-m-d H:i', $date_str)->toDateTimeString();
					
					$event['create_at'] = $date_str;

					//end search create_at
					array_push($arr, $event);
				}
		}
		//end parser
		//add database
		$printing = Printing::where('printer_id',$printer_id)
							->latest('printing_id')
	    					->first();
		$oldPrinting = $printing;
		$max_id = '';
		for($i=0;$i<count($arr);$i++)
		{
			if($oldPrinting['printing_id'] == $arr[$i]['id'])
			{
				$max_id = $i;
			}
		}
		
		if($max_id == count($arr)-1)
		{
			$post['status'] = '1';
			$post['printer_name'] = $printer['printer_name'];
			$post['message'] = 'no updates';
			return json_encode($post);
		}
		else if(empty($max_id))
		{
			for($i=0;$i<count($arr);$i++)
			{
				$printing = new Printing;
				$printing->printer_id = $printer_id;
				$printing->factorie_id = $factorie_id;
				$printing->user_name = $arr[$i]['name'];
				$printing->amount = $arr[$i]['amount'];
				$printing->created_at = $arr[$i]['create_at'];
				$printing->updated_at = $arr[$i]['create_at'];
				$printing->printing_id = $arr[$i]['id'];
				$printing->save();	
			}
		}
		else if($max_id < count($arr)-1)
		{
			for($i=0;$i<count($arr);$i++)
			{
				if($max_id < $i)
				{
					
					$printing = new Printing;
					$printing->printer_id = $printer_id;
					$printing->factorie_id = $factorie_id;
					$printing->user_name = $arr[$i]['name'];
					$printing->amount = $arr[$i]['amount'];
					$printing->created_at = $arr[$i]['create_at'];
					$printing->updated_at = $arr[$i]['create_at'];
					$printing->printing_id = $arr[$i]['id'];
					$printing->save();	
				}
				
			}
		}
		//end database
		$post['status'] = '1';
		$post['printer_name'] = $printer['printer_name'];
		$post['message'] = 'base updated';
		return json_encode($post);
	}
}