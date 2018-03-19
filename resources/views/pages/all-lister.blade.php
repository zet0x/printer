@extends('layouts.template')

@section('content')
<section class="absolute-center animated fadeInDown">
	<div class="container">
		<div class="row">
			<h2 class="all-center">Выберите завод</h2>
			<div class="margin-top-2">
				<form action="/step2" method="get">
					<select name="factorie" class="form-control margin-top" required>
								<option value="9999" selected>По всем принтерам</option>
						@if(!empty($arResult))
							@foreach($arResult as $value)
								<option value="{{ $value['id'] }}">Название: {{ $value['name'] }}, Локация: {{ $value['location'] }}</option>
							@endforeach
						@endif
						
	           			
					</select>
					<div class="margin-top-2 all-center">
						<input type="submit" class="btn" value="Получить">
					</div>
				</form>
				
			</div>
		</div>
	</div>
</section>

@endsection