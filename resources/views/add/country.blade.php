@extends('layouts.template')

@section('content')

<section>
	<div class="container">
		<div class="row">
			<h2 class="all-center">{{ trans('add.add_country') }}</h2>	
			<div class="margin-top-4">
				<form method="post" enctype="multipart/form-data">
					<div class="row">
						<div class="col-xs-3">
							<div>{{ trans('add.register_country') }} <span class="badge">{{ $arResult['count_country'] }}</span></div>
						</div>
						<div class="col-xs-9">
							<div>
								<input class="form-control" type="text" name="name" placeholder="{{ trans('add.country_name') }}" required>
							</div>
							<div class="margin-top-2">
								<input class="form-control" type="text" name="lang" placeholder="{{ trans('add.second_local') }}" required>
							</div>
							<div class="margin-top-2">
								<input class="form-control" type="text" name="lang1" placeholder="{{ trans('add.name1') }}" required>
							</div>
							<div class="margin-top-2">
								<input class="form-control" type="text" name="lang2" placeholder="{{ trans('add.name2') }}" required>
							</div>
							<div class="margin-top-2">
								<select name="continent_id" class="form-control" required>
								    <option selected disabled>{{ trans('add.select_continent') }}т</option>
								    @if(!empty($arResult['continent']))
									    @foreach($arResult['continent'] as $post)
									    <option value="{{ $post['id'] }}">{{ $post['name'] }}</option>
									    @endforeach
								    @endif
							   </select>
							</div>
							<div class="margin-top-2">
								<h3>Флаг</h3>
								<input class="form-control" type="file" name="flag" required>
								<input type="hidden" value="{!! csrf_token() !!}" name="_token">
							</div>

							<div class="margin-top-2 all-center">
								<input class="btn" type="submit" name="factorie" value="{{ trans('add.send') }}">
							</div>
						</div>
						<div class="clearfix"></div>
					</div>
				</form>
			</div>
		</div>
	</div>
</section>

@endsection  
