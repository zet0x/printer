@extends('layouts.template')

@section('content')
	<!-- mobile menu -->
	<nav  class="navbar navbar-default navbar-fixed-top navbar-inverse" role="navigation">
	  
	   	 <div id="logo">Логотип</div>
	  
	</nav>
    <div class="container">
        <div class="row">
            <input type="checkbox" id="nav-toggle" hidden>
            <!-- 
            Выдвижную панель размещаете ниже
            флажка (checkbox), но не обязательно 
            непосредственно после него, например
            можно и в конце страницы
            -->
            <nav class="nav over-hidden">
                <div class="row">
                    <div class="col-xs-8">
                       
                    </div>
                </div>
                <div class="clearfix"></div>
                <!-- 
            Метка с именем `id` чекбокса в `for` атрибуте
            Символ Unicode 'TRIGRAM FOR HEAVEN' (U+2630)
            Пустой атрибут `onclick` используем для исправления бага в iOS < 6.0
            См: http://timpietrusky.com/advanced-checkbox-hack 
            -->
                <label for="nav-toggle" id="nav-btn" class="nav-toggle"></label>    
                

                <!-- 
            Здесь размещаете любую разметку,
            если это меню, то скорее всего неупорядоченный список <ul>
            -->
                
                <ul style="margin:0;"> 
                   <li>
                   		<div class="upercase">Выберите завод</div>
                   		<div>
		  					<select id="region" name="quantity" class="form-control margin-top" required>
									<option disabled selected>Все заводы</option>
								@if(!empty($form['factorie']))
								    @foreach($form['factorie'] as $value)
								    <option value="{{ $value['id'] }}">{{ $value['region'] }}</option>
								    @endforeach
							    @endif
							</select>
		  				</div>
                   </li>
                   <li class="margin-top-2">
                   		<div class="upercase">Как фильтровать</div>
                   		<select disabled id="position" name="quantity" class="form-control margin-top" required>
                   			<option value="none" disabled selected>Выберите</option>
                   			<option value="zavod">По принтерам</option>
                   			<option value="print">По заводам</option>
                   			<option value="date">По дате</option>
						</select>
                   </li>
                   <li id="print" class="margin-top-2 display-none animated">
                   		<div class="upercase">Фильтр по принтерам</div>
                   		<div id="for_print" class="well well-sm">
        		  					<div class="cell color-black all-center">По всем позициям</div>
        		  				</div>
                   		<select name="quantity" class="form-control margin-top" required>

						</select>
                   </li>
                   <li id="zavod" class="margin-top-2 display-none animated">
                   		<div class="upercase">Фильтр по пользователям</div>
                   		<div id="for_user" class="well well-sm">
                   			<div class="cell color-black all-center">По всем позициям</div>
		  				</div>
                   		<select name="quantity" class="form-control margin-top" required>
                   			
						</select>
                   </li>
                   <li id="date" class="margin-top-2 display-none animated">
                   		<div class="upercase">Фильтр по дате</div>
                   		<input type="date" name="calendar" class="form-control">
                   </li>

                   <li class="margin-top-2 all-center display-none animated">
                   		<input class="btn color-black" type="submit" value="Отфильтровать" name="">
                   </li>
                </ul> 
                <script type="text/javascript">
                	var print = document.getElementById('print');
                	var date = document.getElementById('date');
                	var zavod = document.getElementById('zavod');

                	document.addEventListener('DOMContentLoaded',function() {
			    		document.querySelector('#position').onchange=changeEventHandler;
			    		document.querySelector('#region').onchange=addPosition;
			    		zavod.querySelector('select').onchange=addAtrElem;
			    		print.querySelector('select').onchange=addAtrElem;
					},false);

                	document.getElementById('for_print').addEventListener('click',removeAtrElem,false)
                	document.getElementById('for_user').addEventListener('click',removeAtrElem,false)

                	function all_span(li)
                	{
                		span = li.querySelector('span');
                		console.log(span);
                		
                	}

                	function addAtrElem(event)
                	{
                		var options = this.querySelectorAll('option');
                		span = this.parentNode.querySelector('.well').innerHTML;
                		span = span + '<span class="label label-success">'+this.value+'</span> \n';
                		this.parentNode.querySelector('.well').innerHTML = span;
                		if(options.length >= 0)
                		{
                			for(i=0;i<options.length;i++)
                			{
                				if(options[i].value == this.value)
                				{
                					options[i].remove();
                				}
                			}
                		}
                		all_span(this.parentNode);
                		
                	}
                	function removeAtrElem(e)
                	{

                		var event = e || window.event,
                			target = event.CurrentTarget || event.srcElement;
            			if(target.tagName == 'SPAN')
            			{
            				target.parentNode.parentNode.querySelector('select')[target.parentNode.parentNode.querySelectorAll('option').length] = new Option(target.innerHTML,target.innerHTML);
            				target.remove();
            				all_span(this.parentNode);
            			}
            			
                	
                	}

                	function addPosition(event)
                	{
                		if(event.target.value != 'none')
                		{
                			document.querySelector('#position').removeAttribute('disabled');
                			$$a({

						        type:'get',
								url:'/info',
								data:{'value':this.value},
								response:'text',
								success:function (data) {
									data = JSON.parse(data);
									option = zavod.querySelectorAll('option');
									option2 = print.querySelectorAll('option');
									for(k=0;k<option.length;k++)
									{
										option[k].remove();
									}

									for(k=0;k<option2.length;k++)
									{
										option2[k].remove();
									}

									for(i=0;i<data['user'].length;i++)
									{
										print.querySelector('select').options[i] = new Option(data['user'][i]['user'], data['user'][i]['id']);
									}
									for(i=0;i<data['printer'].length;i++)
									{
										zavod.querySelector('select').options[i] = new Option(data['printer'][i]['id'], data['printer'][i]['id']);
									}
								}

						    });

                		}
                		else
                		{
                			document.querySelector('#position').setAttribute('disabled','disabled');
                		}


                	}

					function changeEventHandler(event) {
						zavod.classList.add('display-none');
						date.classList.add('display-none');
						print.classList.add('display-none');
						zavod.classList.add('fadeInLeft');
						date.classList.add('fadeInLeft');
						print.classList.add('fadeInLeft');
						document.getElementById(event.target.value).classList.remove('display-none');
						document.getElementById(event.target.value).classList.add('fadeInLeft');

						

					}
                </script>
                <section class="bottom">
                    <p>Made in <a class="color-white" href="https://octavian48.ru/">octavian</a></p>
                </section>
            </nav>
        </div>
    </div>
    <div id="container-bg"></div>
    <!-- end mobile enu -->
	<section>
		<div class="container">
			<div class="row">
				<div class="col-xs-12">
					<h2 class="all-center">Все напечатанные страницы</h2>
					
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
						   	<tr><td>{{ $value['factorie_id'] }}</td><td>{{ $value['user_id'] }}</td><td>{{ $value['printer_id'] }}</td><td>{{ $value['created_at'] }}</td></tr>
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
	<nav  class="navbar navbar-default navbar-fixed-bottom navbar-inverse" role="navigation">
	  <div class="container">
	   
	  </div>
	</nav>
<script type="text/javascript" src="{{ asset('mobile-menu/js/touch-menu.js') }}"></script>
@endsection 
