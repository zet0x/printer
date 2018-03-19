<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Принтеры</title>

    
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('bootstrap/css/bootstrap-theme.min.css') }}" rel="stylesheet">
    <link href="{{ asset('mobile-menu/css/mobile-menu.css') }}" rel="stylesheet">
    <link href="{{ asset('bootstrap_datetimepicker/css//bootstrap-datetimepicker.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/animate.css') }}" rel="stylesheet">
    <script type="text/javascript" src="http://scriptjava.net/source/scriptjava/scriptjava.js"></script>

    <!-- 3. Подключить библиотеку jQuery -->
    <script src="{{ asset('bootstrap_datetimepicker/js/jquery-3.2.1.min.js') }}"></script>
    <!-- 4. Подключить библиотеку moment -->
    <script src="{{ asset('bootstrap_datetimepicker/js/moment-with-locales.min.js') }}"></script>
    <!-- 5. Подключить js-файл фреймворка Bootstrap 3 -->
    <script src="{{ asset('bootstrap_datetimepicker/js/bootstrap.min.js') }}"></script>
    <!-- 6. Подключить js-файл библиотеки Bootstrap 3 DateTimePicker -->
    <script src="{{ asset('bootstrap_datetimepicker/js/bootstrap-datetimepicker.min.js') }}"></script>
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    
</head>
<body>
@yield('content')
</body>
</html> 
