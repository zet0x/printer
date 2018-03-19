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
	    <li><a href="/country/{{ $arResult['country']['name'] }}/printer">{{ trans('menu.printer') }}</a></li>
	    <li class="active"><a href="/country/{{ $arResult['country']['name'] }}/user">{{ trans('menu.user') }}</a></li>
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
					<div class="col-xs-offset-3 col-xs-6 margin-top-2">
						<input id="user" type='text' class="form-control" name="user" autocomplete="off" placeholder="{{ trans('diagramm.user') }}">
					</div>
					<div class="clearfix"></div>
					<div class="col-xs-offset-3 col-xs-6 margin-top-2">
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
					<div class="all-center margin-top-2">
						<button id="subm" class="btn">{{ trans('diagramm.refresh') }}</button>
					</div>
					<div class="clearfix"></div>
				
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
	document.getElementById('subm').addEventListener('click',jsonSubmit,false);

	function jsonSubmit()
	{

		deleteDuagramm();
		document.querySelector('.load').style.display = 'block';


		date_after = document.querySelector('#datetimepicker-after');
		date_before = document.querySelector('#datetimepicker-before');
		user_name = document.querySelector('#user');
		region_id = document.querySelector('#region');

		
		

		if(user.value != '' && region_id.value != 'none')
		{
			json = {
				region_id : region_id.value,
				user_name :  user_name.value,
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
			document.querySelector('.load-text h3').innerHTML = 'Error: Choose region and user name';
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
	  xkey: 'printer_name',
	  // A list of names of data record attributes that contain y-values.
	  ykeys: ['amount'],
	  // Labels for the ykeys -- will be displayed when you hover over the
	  // chart.
	  labels: ['Pages']
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