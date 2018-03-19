@extends('layouts.main_template')

@section('content')
<nav class="navbar navbar-default navbar-static-top" role="navigation">
  <div class="container">
     <ul class="nav navbar-nav">
     	<a style="padding-top: 3px" class="navbar-brand" href="/"><img src="{{ asset('img/logo.png') }}"></a>
	    
	  </ul>
	 
  </div>
</nav>
<section>
	<div class="container">
		<div class="row">
			<h2 class="all-center">{{ trans('diagramm.country') }}</h2>
			<div class="margin-top-8"></div>
			<?php $num = 1; ?>
			@if(!empty($arResult))
				@foreach($arResult as $value)
					<div class="col-xs-4">
						<a href="/country/{{ $value['name'] }}">
							<div class="all-center">
								<img src="{{ asset('') }}/{{ $value['flag'] }}">
							</div>
							<h3 class="all-center">{{ json_decode($value['content'])->en->title }}</h3>
						</a>
					</div>

					@if($num % 3 == 0)
					<div class="clearfix"></div>
					@endif
					<?php $num = $num +1; ?>
				@endforeach
			@endif
		</div>
	</div>
</section>

@endsection  

