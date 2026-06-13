@extends('layouts.app')

@section('title', 'Вход')

@section('content')
<div class="row row--nogutter top-line">
    <div class="line"></div>
</div>
<div class="main">
    <div class="row">
        <div class="row--small">
            
            @if ($errors->any())
                <div style="color: red; margin-bottom: 15px;">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('login.post') }}" method="POST">
                @csrf
                <h2>Вход в личный кабинет</h2>
                
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required>
                </div>
                
                <div class="form-group">
                    <label>Пароль</label>
                    <input type="password" name="password" required>
                </div>
                
                <div class="form-group">
                    <button type="submit" class="btn">Войти</button>
                </div>
                
                <div class="form-group" style="margin-top: 15px;">
                    <a href="{{ route('register') }}">Нет аккаунта? Зарегистрироваться</a>
                </div>
            </form>
        </div>
    </div>  
</div>
<div class="row row--nogutter">
    <div class="line"></div>
</div>
@endsection