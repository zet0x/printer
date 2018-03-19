@extends('layouts.template')

@section('content')
	
	<section>
		<div class="container">
			<div class="row">
				<div class="col-xs-12">
					<h2 class="all-center">%НАЗВАНИЕ ЗАВОД%</h2>
					
					<div class="panel panel-default margin-top-2">
					  <!-- Default panel contents -->
					  <div class="panel-heading">Результат запроса</div>

					  <!-- Table -->
					  <table class="table">
					    <tr>
					    <th class="all-center">Завод</th>
					    <th class="all-center">Пользователь</th>
					    <th class="all-center">Принтер</th>
					    <th class="all-center">Дата</th>
					   </tr>
					   @if(!empty($arResult))
						   @foreach($arResult as $value)
						   	<tr><td>{{ $value['factorie_id'] }}</td><td>{{ $value['user_name'] }}</td><td>{{ $value['printer_id'] }}</td><td>{{ $value['created_at'] }}</td></tr>
						   @endforeach
					   @endif
					  </table>
					</div>
				</div>
			</div>
		</div>
	</section>
	<section>
		<div class="container">
			<div class="row">
				<div class="all-right">
					<?php echo $arResult->links(); ?>	
				</div>
			</div>
		</div>
	</section>
@endsection 
