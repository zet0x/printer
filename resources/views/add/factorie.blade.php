@extends('layouts.template')

@section('content')

<section>
	<div class="container">
		<div class="row">
			<h2 class="all-center">{{ trans('add.add_factorie') }}</h2>	
			<div class="margin-top-4">
				<form method="get">
					<div class="row">
						<div class="col-xs-3">
							<div>{{ trans('add.register_factorie') }} <span class="badge">{{ $arResult['count_factorie'] }}</span></div>
						</div>
						<div class="col-xs-9">
							<div>
									<select name="country_id" class="form-control" required>
									    <option selected disabled>{{ trans('add.select_country') }}</option>
									    @if(!empty($arResult['country']))
										    @foreach($arResult['country'] as $post)
										    <option value="{{ $post['id'] }}">{{ $post['name'] }}</option>
										    @endforeach
									    @endif
								   </select>
								</div>
							<div class="margin-top-2">
								<input class="form-control" type="text" name="name" placeholder="{{ trans('add.factorie_name') }}" required>
							</div>
							<div class="margin-top-2">
								<input class="form-control" type="text" name="location" placeholder="{{ trans('add.location') }}" required>
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
