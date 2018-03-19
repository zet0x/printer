@extends('layouts.main_template')
<?php $lang = $allLocal['active']; ?>
@section('content')

<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.css">
<script src="//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js"></script>
<nav class="navbar navbar-default navbar-static-top" role="navigation">
  <div class="container">
     <ul class="nav navbar-nav">
     	<a style="padding-top: 3px" class="navbar-brand" href="/"><img src="{{ asset('img/logo.png') }}"></a>
	    @if(!empty($arResult['country']))
	    <li><a href="/country/{{ $arResult['country']['name'] }}">{{ trans('menu.main') }}</a></li>
	    <li class="active"><a href="/country/{{ $arResult['country']['name'] }}/printer">{{ trans('menu.printer') }}</a></li>
	    <li><a href="/country/{{ $arResult['country']['name'] }}/user">{{ trans('menu.user') }}</a></li>
	    <li><a href="/country/{{ $arResult['country']['name'] }}/export">{{ trans('menu.export') }}</a></li>
	    @endif
	  </ul>
	  <div class="btn-group navbar-right">
		    <ul class="nav navbar-nav">
		    	<a class="navbar-brand" href="">{{ trans('menu.localization') }}:</a>
		    	@if(!empty($allLocal))
		    		@foreach($allLocal['all'] as $value)
		    			@if($value['lang'] == $allLocal['active'])
		    			<li class="active"><a href="/lang/{{ $value['lang'] }}">{{ $value['lang'] }}</a></li>
		    			@else
		    			<li><a href="/lang/{{ $value['lang'] }}">{{ $value['lang'] }}</a></li>
		    			@endif
		    		@endforeach
		    	@endif

		    </ul>
		</div>
  </div>
</nav>

<section>
	<div class="container">
		<div class="row">
			@if(empty(json_decode($arResult['country']['content'])->$lang))
			<h2 class="all-center">{{ json_decode($arResult['country']['content'])->en->title }}</h2>
			@else
			<h2 class="all-center">{{ json_decode($arResult['country']['content'])->$lang->title }}</h2>
			@endif
			<div class="col-xs-6">
				
					<div class="margin-top-2">
						<select id="region" name="region_id" class="form-control margin-top" required>
							<option value="none" disabled selected>{{ trans('diagramm.region') }}</option>
							@if(!empty($arResult['factorie']))
								@foreach($arResult['factorie'] as $value)
									<option class="regons-name" value="{{ $value['id'] }}">{{ $value['location'] }}</option>
								@endforeach
							@endif
						</select>
					</div>
					<div class="clearfix"></div>
					<div class="margin-top-2">
						<select id="printer" name="printer_id" class="form-control margin-top" disabled required>
							
						</select>
					</div>
					<div class="clearfix"></div>
					<div class="margin-top-2">
						<div class="row">
							<div class="col-xs-6">
								<div class="all-center">{{ trans('diagramm.from') }}</div>
								<input id='datetimepicker-after' type='text' class="form-control" name="date-before" />
							</div>
							<div class="col-xs-6">
								<div class="all-center">{{ trans('diagramm.before') }}</div>
								<input id='datetimepicker-before' type='text' class="form-control" name="date-after" />
							</div>
							<div class="clearfix"></div>
						</div>
					</div>
					
					<div class="clearfix"></div>
				
			</div>
			<div class="col-xs-6">
				<div class="margin-top-2">
					<div class="panel panel-default">
					  <div class="panel-heading">{{ trans('diagramm.information_printer') }}</div>
					  <div class="panel-body">
					    <span style="font-weight: 800;">{{ trans('diagramm.model') }} </span><span id="printer_model"></span>
					  </div>
					  <div class="panel-body">
					    <span style="font-weight: 800;">{{ trans('diagramm.type') }} </span><span id="printer_type"></span>
					  </div>
					  <div class="panel-body">
					    <span style="font-weight: 800;">{{ trans('diagramm.location') }} </span><span id="printer_locacion"></span>
					  </div>
					  <div class="panel-body">
					    <span style="font-weight: 800;">{{ trans('diagramm.notes') }} </span><span id="printer_notes"></span>
					  </div>
					</div>
				</div>
			</div>
			<div class="clearfix"></div>
					<div class="all-center margin-top-2">
						<button id="subm" class="btn">{{ trans('diagramm.refresh') }}</button>
					</div>

			<div class="col-xs-12 margin-top-2">
				<div id="graphic">
					<div id="myfirstchart" style="height: 400px;">
						<div class="load-text ">
							<h3 class="all-center margin-top-4">{{ trans('diagramm.non') }}</h3>
							<div class="all-center load margin-top-4">
								<img src="{{ asset('img/load.gif') }}">
							</div>	
						</div>
						
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
<script type="text/javascript">

//События

document.getElementById('subm').addEventListener('click',jsonSubmit,false);

document.addEventListener('DOMContentLoaded',function() 
{
	
	document.querySelector('#region').onchange=addOption;

},false);
//Отправка json для построения 

function addOption()
{
	document.querySelector('#printer').removeAttribute('disabled');
	$$a({

        type:'get',
		url:'/country/russia/printer/factorie',
		data:{'value':this.value},
		response:'text',
		success:function (data) {
			data = JSON.parse(data);
			option = document.getElementById('printer').querySelectorAll('option');
				for(k=0;k<option.length;k++)
				{
					option[k].remove();
				}
			if(data.length == 0)
			{
				document.getElementById('printer').options[0] = new Option('NONE','none');
			}
			else
			{
				

				for(i=0;i<data['printer'].length;i++)
				{
					document.getElementById('printer').options[i] = new Option(data['printer'][i]['printer_name'],data['printer'][i]['id']);
				}	
			}
			
			
		}

    });
}

function jsonSubmit()
{

	deleteDuagramm();
	document.querySelector('.load').style.display = 'block';


	date_after = document.querySelector('#datetimepicker-after');
	date_before = document.querySelector('#datetimepicker-before');
	printer_id = document.querySelector('#printer');
	region_id = document.querySelector('#region');


	if(printer_id.value != 'none')
	{
		json = {
			region_id : region_id.value,
			printer_id :  printer_id.value,
			date_before :  date_before.value,
			date_after :  date_after.value
		}

		json = JSON.stringify(json);
		$$a({

	        type:'get',
			url:'/country/russia/printer/info',
			data:{'value': json},
			response:'text',
			success:function (data) {
				data = JSON.parse(data);
				if(data['printer_info'] != undefined)
				{
					document.getElementById('printer_model').innerHTML = data['printer_info']['model'];
					document.getElementById('printer_type').innerHTML = data['printer_info']['color'];
					document.getElementById('printer_locacion').innerHTML = data['printer_info']['location'];
					document.getElementById('printer_notes').innerHTML = data['printer_info']['notes'];
				}

				if(data['status'] == 1) 
				{
					printer_model
					printer_type
					printer_locacion
					printer_notes

					
					duagramm(data['top']);
					document.querySelector('.load').style.display = 'none';
					document.querySelector('.load-text h3').style.display = 'none';
				}
				else
				{
					document.querySelector('.load-text h3').style.display = 'block';
					document.querySelector('.load-text h3').innerHTML = 'Error: ' + data['error'];
					document.querySelector('.load').style.display = 'none';
				}
			}

	    });
	}
	else
	{
		document.querySelector('.load-text h3').innerHTML = 'Error: Choose region';
		document.querySelector('.load').style.display = 'none';
	}

	
}

function deleteDuagramm()
{
	if(document.querySelector('svg') && document.querySelector('.morris-hover'))
	{
		document.querySelector('svg').remove();
		document.querySelector('.morris-hover').remove();
	}
}
//Конец

//Диаграмма

function duagramm(top)
{
	

	  new Morris.Bar({
	  // ID of the element in which to draw the chart.
	  element: 'myfirstchart',
	  // Chart data records -- each entry in this array corresponds to a point on
	  // the chart.
	  data: top,
	  // The name of the data record attribute that contains x-values.
	  xkey: 'user_name',
	  // A list of names of data record attributes that contain y-values.
	  ykeys: ['amount'],
	  // Labels for the ykeys -- will be displayed when you hover over the
	  // chart.
	  labels: ['Страниц']
	});
}
	

	
</script>

<script type="text/javascript">
	$(function () {  
		var month = moment().get('month') + 1;  
    	var year = moment().get('year');  

	  $('#datetimepicker-after').datetimepicker({
	  	format: 'YYYY-MM-DD',
	  	defaultDate: year+'-'+month+'-01'
	  });

	  $('#datetimepicker-before').datetimepicker({
	  	format: 'YYYY-MM-DD',
	  	defaultDate: moment(),
	  	useCurrent: false
	  });

	  
	  $("#datetimepicker-after").on("dp.change", function (e) { 
	    $('#datetimepicker-before').data("DateTimePicker").minDate(e.date.add(1, 'days')); 
	    if(moment(e.date).isAfter($('#datetimepicker-before').data("DateTimePicker").date())){
	      $	('#datetimepicker-before').data("DateTimePicker").date(e.date);
	    }
	  });

	});
</script>

@endsection 
