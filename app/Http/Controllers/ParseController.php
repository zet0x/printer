<?php

namespace App\Http\Controllers;

use App\Printer;
use Carbon\Carbon;
use App\Helpers\Parser\Parse;

class ParseController extends Controller
{
	private function writeLog($arr)
	{
		$file = file_get_contents(public_path().'/log/parser_log.txt');
		$out = fopen(public_path().'/log/parser_log.txt', 'w');
		$date = Carbon::now();
		fwrite($out, $file.PHP_EOL. $date. PHP_EOL);
		fwrite($out, '-----------------------'. PHP_EOL);
		foreach ($arr as $key => $value) {
			fwrite($out, $value. PHP_EOL); 
		}
		fwrite($out, '-----------------------'. PHP_EOL);
		fclose($out);
	}

	function allUpdates()
	{
		$arr = array();

		$printers = Printer::get();

		for ($i=0; $i < count($printers); $i++) { 
			$id = $printers[$i]['id'];
			//Обновить базу 
			array_push($arr, Parse::parsePrinter($id));
		}
		$this->writeLog($arr);
	}

}
