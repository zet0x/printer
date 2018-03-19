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
	    <li class="active"><a href="/country/{{ $arResult['country']['name'] }}">{{ trans('menu.main') }}</a></li>
	    <li><a href="/country/{{ $arResult['country']['name'] }}/printer">{{ trans('menu.printer') }}</a></li>
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
			
			<div>
				
					
					<div class="col-xs-offset-3 col-xs-6 margin-top-2">
						<select id="region" name="printer_id" class="form-control margin-top" required>
							<option disabled selected>{{ trans('diagramm.region') }}</option>
							@if(!empty($arResult['factorie']))
								@foreach($arResult['factorie'] as $value)
									<option value="{{ $value['id'] }}">{{ $value['location'] }}</option>
								@endforeach
							@endif
						</select>
					</div>
					<div class="clearfix"></div>
					
					<div class="margin-top-2">
						<div class="col-xs-6">
							<div class="row">
								
								<div class="col-xs-6">
									<div class="all-center">{{ trans('diagramm.from') }}</div>
									<input id='print-datetimepicker-after' type='text' class="form-control" name="date-before" />
								</div>
								<div class="col-xs-6">
									<div class="all-center">{{ trans('diagramm.before') }}</div>
									<input id='print-datetimepicker-before' type='text' class="form-control" name="date-after" />
								</div>

								<div class="clearfix"></div>
								<div class="all-center margin-top-2">
									<input id="print-subm" type="submit" value="{{ trans('diagramm.refresh') }}" class="btn">
								</div>
								<div class="margin-top-2">
									<h3 class="all-center">{{ trans('diagramm.top5u') }}</h3>
								</div>
								<div class="col-xs-12 margin-top-2">
									<div id="print-graphic">
										<div id="print-myfirstchart" style="height: 400px;">
											<h3 class="all-center margin-top-4">{{ trans('diagramm.non') }}</h3>
											<div class="all-center load margin-top-4">
												<img src="{{ asset('img/load.gif') }}">
											</div>	
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-xs-6">
							<div class="row">
								
								<div class="col-xs-6">
									<div class="all-center">{{ trans('diagramm.from') }}</div>
									<input id='user-datetimepicker-after' type='text' class="form-control" name="date-before" />
								</div>
								<div class="col-xs-6">
									<div class="all-center">{{ trans('diagramm.before') }}</div>
									<input id='user-datetimepicker-before' type='text' class="form-control" name="date-after" />
								</div>

								<div class="clearfix"></div>
								<div class="all-center margin-top-2">
									<input id="user-subm" type="submit" value="{{ trans('diagramm.refresh') }}" class="btn">
								</div>
								<div class="margin-top-2">
									<h3 class="all-center">{{ trans('diagramm.top5p') }}</h3>
								</div>
								<div class="col-xs-12 margin-top-2">
									<div id="user-graphic">
										<div id="user-myfirstchart" style="height: 400px;">
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
					

					
					
					<div class="clearfix"></div>
				
			</div>
			
		</div>
	</div>
</section>
<script type="text/javascript">
document.getElementById('print-subm').addEventListener('click',printJsonSubmit,false);
document.getElementById('user-subm').addEventListener('click',userJsonSubmit,false);


function printJsonSubmit()
	{

		printDeleteDuagramm();
		document.getElementById('print-graphic').querySelector('.load').style.display = 'block';

		date_after = document.querySelector('#print-datetimepicker-after');
		date_before = document.querySelector('#print-datetimepicker-before');
		region_id = document.querySelector('#region');

		
		

		if(region_id.value != 'none')
		{
			json = {
				region_id : region_id.value,
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
					if(data['status'] == 1) 
					{
						printDuagramm(data['top']);
						document.getElementById('print-graphic').querySelector('.load').style.display = 'none';
						document.getElementById('print-graphic').querySelector('h3').style.display = 'none';
					}
					else
					{
						document.getElementById('print-graphic').querySelector('h3').style.display = 'block';
						document.getElementById('print-graphic').querySelector('h3').innerHTML = 'Error: ' + data['error'];
						document.getElementById('print-graphic').querySelector('.load').style.display = 'none';
					}
				}

		    });
		}

		
	}
function userJsonSubmit()
	{

		userDeleteDuagramm();
		document.getElementById('user-graphic').querySelector('.load').style.display = 'block';

		date_after = document.querySelector('#user-datetimepicker-after');
		date_before = document.querySelector('#user-datetimepicker-before');
		region_id = document.querySelector('#region');

		
		

		if(region_id.value != 'none')
		{
			json = {
				region_id : region_id.value,
				date_before :  date_before.value,
				date_after :  date_after.value
			}

			json = JSON.stringify(json);
			$$a({

		        type:'get',
				url:'/country/russia/user/info',
				data:{'value': json},
				response:'text',
				success:function (data) {
					data = JSON.parse(data);
					if(data['status'] == 1) 
					{
						userDuagramm(data['top']);
						document.getElementById('user-graphic').querySelector('.load').style.display = 'none';
						document.getElementById('user-graphic').querySelector('h3').style.display = 'none';
					}
					else
					{
						document.getElementById('user-graphic').querySelector('h3').style.display = 'block';
						document.getElementById('user-graphic').querySelector('h3').innerHTML = 'Error: ' + data['error'];
						document.getElementById('user-graphic').querySelector('.load').style.display = 'none';
					}
				}

		    });
		}

		
	}

function printDeleteDuagramm()
{
	if(document.getElementById('print-myfirstchart').querySelector('svg') && document.getElementById('print-myfirstchart').querySelector('.morris-hover'))
	{
		document.getElementById('print-myfirstchart').querySelector('svg').remove();
		document.getElementById('print-myfirstchart').querySelector('.morris-hover').remove();
	}
}

function userDeleteDuagramm()
{
	if(document.getElementById('user-myfirstchart').querySelector('svg') && document.getElementById('user-myfirstchart').querySelector('.morris-hover'))
	{
		document.getElementById('user-myfirstchart').querySelector('svg').remove();
		document.getElementById('user-myfirstchart').querySelector('.morris-hover').remove();
	}
}

function printDuagramm(top)
{
	

	  new Morris.Bar({
	  // ID of the element in which to draw the chart.
	  element: 'print-myfirstchart',
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

function userDuagramm(top)
{
	

	  new Morris.Bar({
	  // ID of the element in which to draw the chart.
	  element: 'user-myfirstchart',
	  // Chart data records -- each entry in this array corresponds to a point on
	  // the chart.
	  data: top,
	  // The name of the data record attribute that contains x-values.
	  xkey: 'printer_name',
	  // A list of names of data record attributes that contain y-values.
	  ykeys: ['amount'],
	  // Labels for the ykeys -- will be displayed when you hover over the
	  // chart.
	  labels: ['Страниц']
	});
}

	
</script>

<script>

    $(function () {
    var month = moment().get('month') + 1;  
    var year = moment().get('year');

	  $('#print-datetimepicker-after').datetimepicker({
	  	format: 'YYYY-MM-DD',
	  	defaultDate: year+'-'+month+'-01'
	  });

	  $('#print-datetimepicker-before').datetimepicker({
	  	format: 'YYYY-MM-DD',
	  	defaultDate: moment(),
	  	useCurrent: false
	  });

	  
	  $("#print-datetimepicker-after").on("dp.change", function (e) { 
	    $('#print-datetimepicker-before').data("DateTimePicker").minDate(e.date.add(1, 'days')); 
	    if(moment(e.date).isAfter($('#print-datetimepicker-before').data("DateTimePicker").date())){
	      $	('#print-datetimepicker-before').data("DateTimePicker").date(e.date);
	    }
	  });

	});

	$(function () {      
		var month = moment().get('month') + 1;  
    	var year = moment().get('year');    

	  $('#user-datetimepicker-after').datetimepicker({
	  	format: 'YYYY-MM-DD',
	  	defaultDate: year+'-'+month+'-01'
	  });

	  $('#user-datetimepicker-before').datetimepicker({
	  	format: 'YYYY-MM-DD',
	  	defaultDate: moment(),
	  	useCurrent: false
	  });

	  
	  $("#user-datetimepicker-after").on("dp.change", function (e) { 
	    $('#user-datetimepicker-before').data("DateTimePicker").minDate(e.date.add(1, 'days')); 
	    if(moment(e.date).isAfter($('#user-datetimepicker-before').data("DateTimePicker").date())){
	      $	('#user-datetimepicker-before').data("DateTimePicker").date(e.date);
	    }
	  });

	});

	
</script>

@endsection  
