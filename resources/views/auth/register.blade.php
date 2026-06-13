@extends('layouts.app')

@section('title', 'Регистрация')

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

            <form action="{{ route('register.post') }}" method="POST">
                @csrf
                <h2>Форма регистрации</h2>
                
                <div class="form-group">
                    <label>ФИО</label>
                    <input type="text" name="name" value="{{ old('name') }}" required>
                </div>
                
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required>
                </div>
                
                <div class="form-group">
                    <label>Пароль</label>
                    <input type="password" name="password" required>
                </div>
                
                <div class="form-group">
                    <label>Номер телефона</label>
                    <input type="tel" name="phone" value="{{ old('phone') }}" required>
                </div>
                
                <div class="form-group">
                    <button type="submit" class="btn">Отправить</button>
                </div>
                <div class="form-group" style="margin-top: 15px;">
                    <a href="{{ route('login') }}">Уже зарегистрированы? Войти</a>
                </div>
            </form>
        </div>
    </div>  
</div>
<div class="row row--nogutter">
    <div class="line"></div>
</div>
@endsection