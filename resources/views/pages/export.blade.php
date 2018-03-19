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
	    <li><a href="/country/{{ $arResult['country']['name'] }}/user">{{ trans('menu.user') }}</a></li>
	    <li class="active"><a href="/country/{{ $arResult['country']['name'] }}/export">{{ trans('menu.export') }}</a></li>
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
					<form method="get">
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
							<div class="row">
								<div class="col-xs-6">
									<div class="all-center">{{ trans('diagramm.from') }}</div>
									<input id='datetimepicker-after' type='text' class="form-control" name="date-after" />
								</div>
								<div class="col-xs-6">
									<div class="all-center">{{ trans('diagramm.before') }}</div>
									<input id='datetimepicker-before' type='text' class="form-control" name="date-before" />
								</div>
								<div class="clearfix"></div>
							</div>
						</div>
						<div class="clearfix"></div>
						<div class="all-center margin-top-2">
							<button id="subm" type="sibmit" class="btn">{{ trans('diagramm.load') }}</button>
						</div>
						<div class="clearfix"></div>
					</form>
			</div>
		</div>
	</div>
</section>


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
