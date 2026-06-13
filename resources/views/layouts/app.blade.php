<!DOCTYPE html>
<html>
<head>
    <title>@yield('title', 'Клуб любителей творчества')</title>
    <link rel="stylesheet" type="text/css" href="{{ asset('css/styles.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/responsive.css') }}">
</head>
<body class="@yield('body_class')">
    <div class="header">
        <div class="row grid middle between">
            <div class="logo">
                <img src="{{ asset('img/logo.png') }}" alt="Логотип">
            </div>
            <div class="title">
                Клуб любителей творчества «ОчУмелые ручки»
            </div>
            <div class="auth">
                @guest
                    <a href="{{ route('login') }}">Вход/Регистрация</a>
                @else
                    <a href="{{ route('cabinet') }}">Личный кабинет ({{ Auth::user()->name }})</a>
                    <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                        @csrf
                        <button type="submit" style="background:none; border:none; color:blue; cursor:pointer;">Выход</button>
                    </form>
                @endguest
            </div>
        </div>
    </div>
    
    <div class="row row--nogutter">
        <div class="menu-burger">
            <div class="burger"><div></div><div></div><div></div></div>
        </div>  
    </div>

    @yield('content')

    <div class="footer">
        <div class="row">
            <div class="row--small grid between">
                <div class="address">Наш адрес: ВДНХ, 120в</div>
                <div class="tel">Тел: 89123456765</div>
                <div class="copy">(с) Copyright, {{ date('Y') }}</div>
            </div>
        </div>
    </div>
</body>
</html>