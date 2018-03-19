@extends('layouts.template')

@section('content')
	<section>
		<div class="container">
			<div class="row">
				<h2 class="all-center">Добавить завод</h2>	
				<div class="margin-top-4">
					<form method="get">
						<div class="row">
							<div class="col-xs-3">
								<div>Колличество зарегестрированных заводов: <span class="badge">{{ $arResult['count_factorie'] }}</span></div>
							</div>
							<div class="col-xs-9">
								<div>
									<input class="form-control" type="text" name="name" placeholder="Название завода" required>
								</div>
								<div class="margin-top-2">
									<input class="form-control" type="text" name="location" placeholder="Место положения" required>
								</div>

								<div class="margin-top-2 all-center">
									<input class="btn" type="submit" name="factorie" value="Отправить">
								</div>
							</div>
							<div class="clearfix"></div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</section>

	<section>
		<div class="container">
			<div class="row">
				<h2 class="all-center">Добавить принтер</h2>	
				<div class="margin-top-4">
					<form method="get">
						<div class="row">
							<div class="col-xs-3">
								<div>Колличество зарегестрированных принтеров: <span class="badge">{{ $arResult['count_printer'] }}</span></div>
							</div>
							<div class="col-xs-9">
								
								<div>
									<select name="print_factorie" class="form-control" required>
									    <option selected disabled>Выберите в какой завод добавить</option>
									    @if(!empty($arResult['factorie']))
										    @foreach($arResult['factorie'] as $post)
										    <option value="{{ $post['id'] }}">Название: {{ $post['name'] }}, Локация: {{ $post['location'] }}</option>
										    @endforeach
									    @endif
								   </select>
								</div>

								<div class="margin-top-2">
									<input class="form-control" type="text" name="site" placeholder="Местонахождение">
								</div>

								<div class="margin-top-2">
									<input class="form-control" type="text" name="lang" placeholder="Локализация">
								</div>

								<div class="margin-top-2">
									<input class="form-control" type="text" name="printer_name" placeholder="Имя">
								</div>

								<div class="margin-top-2">
									<input class="form-control" type="text" name="model" placeholder="Модель">
								</div>

								<div class="margin-top-2">
									<input class="form-control" type="text" name="color" placeholder="Краска">
								</div>

								<div class="margin-top-2">
									<input class="form-control" type="text" name="link" placeholder="Ссылка">
								</div>

								<div class="margin-top-2">
									<input class="form-control" type="text" name="location" placeholder="Локация">
								</div>

								<div class="margin-top-2">
									<input class="form-control" type="text" name="notes" placeholder="Дополнительно">
								</div>

								<div class="margin-top-2 all-center">
									<input class="btn" type="submit" name="printer" value="Отправить">
								</div>
							</div>
							<div class="clearfix"></div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</section>

	<section class="ser">
		<div class="container">
			<div class="row">
				<h2 class="all-center">Симулировать печать</h2>	
				<div class="margin-top-4">
					<form method="get">
						<div class="row">
							<div class="col-xs-3">
								<div>Всего распечатано страниц: <span class="badge">{{ $arResult['count_pristing'] }}</span></div>
							</div>
							<div class="col-xs-9">
								
								<div>
									<select id="selectId1" name="printing_factorie" class="form-control" onchange="select1(this.options[this.selectedIndex].value)" required>
									    <option selected disabled>В каком заводе</option>
									    @if(!empty($arResult['factorie']))
										    @foreach($arResult['factorie'] as $post)
										    <option value="{{ $post['id'] }}">Название: {{ $post['name'] }}, Локация: {{ $post['location'] }}</option>
										    @endforeach
									    @endif
								   </select>
								</div>

								

								<div class="margin-top-2">
									<select id="printer-list" name="printing_print" class="form-control" required>
									    <option selected disabled>На каком принтере</option>
									    
								   </select>
								</div>
								<div class="margin-top-2">
									<input class="form-control" type="text" name="user_name" placeholder="Имя пользователя" required>
								</div>
								<div class="margin-top-2">
									<div class="all-center">Колличество страниц</div>
									<select name="quantity" class="form-control margin-top-2" required>
										<option selected value="1">1</option>
										<option value="2">2</option>
										<option value="3">3</option>
										<option value="4">4</option>
										<option value="5">5</option>
										<option value="6">6</option>
										<option value="7">7</option>
										<option value="8">8</option>
										<option value="9">9</option>
										<option value="1">10</option>
									</select>
								</div>
								<div class="margin-top-2 all-center">
									<input class="btn" type="submit" value="Печать">
								</div>
							</div>
							<div class="clearfix"></div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</section>
	<script type="text/javascript">
		var printer = document.querySelector('#printer-list');			
		
		function select1(value)
		{
			console.log(value);
			$$a({

		        type:'get',//тип запроса: get,post либо head
				url:'/info',//url адрес файла обработчика
				data:{'value':value},//параметры запроса
				response:'text',//тип возвращаемого ответа text либо xml
				success:function (data) {//возвращаемый результат от сервера

					data = JSON.parse(data);
					option = printer.querySelectorAll('option');
					for(k=0;k<option.length;k++)
					{
						option[k].remove();
					}
					for(i=0;i<data['printer'].length;i++)
					{
						printer.options[i] = new Option(data['printer'][i]['model']+' - '+data['printer'][i]['location'], data['printer'][i]['id']);
					}
					
				}

		    });

		}
	</script>
	
@endsection
