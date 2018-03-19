@extends('layouts.template')

@section('content')

<section>
		<div class="container">
			<div class="row">
				<h2 class="all-center">{{ trans('add.add_printer') }}</h2>	
				<div class="margin-top-4">
					<form method="get">
						<div class="row">
							<div class="col-xs-3">
								<div>{{ trans('add.register_printer') }} <span class="badge">{{ $arResult['count_printer'] }}</span></div>
							</div>
							<div class="col-xs-9">
								
								<div>
									<select name="print_factorie" class="form-control" required>
									    <option selected disabled>{{ trans('add.select_factorie') }}</option>
									    @if(!empty($arResult['factorie']))
										    @foreach($arResult['factorie'] as $post)
										    <option value="{{ $post['id'] }}">{{ $post['location'] }}</option>
										    @endforeach
									    @endif
								   </select>
								</div>

								<div class="margin-top-2">
									<input class="form-control" type="text" name="site" placeholder="{{ trans('add.site') }}">
								</div>

								<div class="margin-top-2">
									<input class="form-control" type="text" name="lang" placeholder="{{ trans('add.localization') }}">
								</div>

								<div class="margin-top-2">
									<input class="form-control" type="text" name="printer_name" placeholder="{{ trans('add.printer_name') }}">
								</div>

								<div class="margin-top-2">
									<input class="form-control" type="text" name="model" placeholder="{{ trans('add.model') }}">
								</div>

								<div class="margin-top-2">
									<input class="form-control" type="text" name="color" placeholder="{{ trans('add.color') }}">
								</div>

								<div class="margin-top-2">
									<input class="form-control" type="text" name="link" placeholder="{{ trans('add.link') }}">
								</div>

								<div class="margin-top-2">
									<input class="form-control" type="text" name="location" placeholder="{{ trans('add.location') }}">
								</div>

								<div class="margin-top-2">
									<input class="form-control" type="text" name="notes" placeholder="{{ trans('add.notes') }}">
								</div>

								<div class="margin-top-2 all-center">
									<input class="btn" type="submit" name="printer" value="{{ trans('add.send') }}">
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
