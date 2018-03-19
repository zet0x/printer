@extends('layouts.template')

@section('content')
<style type="text/css">
	.input-group
	{
		display: block !important;
	}
</style>

<section class="absolute-center animated fadeInDown">
	<div class="container">
		<div class="row">
			<h2 class="all-center">"{{ $arResult['factorie']['name'] }}"</h2>
			<div class="row margin-top-4">
				<div class="col-xs-offset-3 col-xs-6">
					<h3 class="all-center">По принтерам</h3>
					<form action="/step3">
						<select name="printer_id" class="form-control margin-top" required>
							<option value="9999" selected>По всем принтерам</option>
                   			
							@if(!empty($arResult['printer']))
								@foreach($arResult['printer'] as $value)
									<option value="{{ $value['id'] }}">{{ $value['model'] }} - {{ $value['location'] }}</option>
								@endforeach
							@endif
						</select>
						<div class="form-group">
			                <div class='input-group date' >
			                    С <input id='datetimepicker1' type='text' class="form-control" name="date-before" />
			                </div>
			                <div class='input-group date'>
			                    По <input id='datetimepicker2' type='text' class="form-control" name="date-after" />
			                </div>
			            </div>
						<div class="all-center margin-top-2">
							<input type="submit" value="Получить" class="btn">
						</div>
					</form>
				</div>
				
				
				</div>
				<div class="clearfix"></div>
				<section>
					<div class="margin-top-2"></div>
				</section>
			</div>
		</div>
	</div>
</section>
<section>
	
</section>

<script>
    $(function () {                
	  $('#datetimepicker1').datetimepicker({
	  	format: 'YYYY-MM-DD'
	  });

	  $('#datetimepicker2').datetimepicker({
	  	format: 'YYYY-MM-DD',
	  	useCurrent: false
	  });

	  $("#datetimepicker1").on("dp.change", function (e) { 
	    $('#datetimepicker2').data("DateTimePicker").minDate(e.date.add(1, 'days')); 
	    if(moment(e.date).isAfter($('#datetimepicker2').data("DateTimePicker").date())){
	      $	('#datetimepicker2').data("DateTimePicker").date(e.date);
	    }
	  });
	});

	
</script>

@endsection