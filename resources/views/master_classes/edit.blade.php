@extends('layouts.app')
@section('title', 'Редактирование мастер-класса')

@section('content')
<div class="row row--nogutter top-line"><div class="line"></div></div>
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

            <form action="{{ route('master-class.update', $masterClass->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <h2>Редактирование мастер-класса: {{ $masterClass->title }}</h2>
                <p>Дата: {{ \Carbon\Carbon::parse($masterClass->date)->format('d.m.Y') }} | Время: {{ $masterClass->time_slot }}</p>

                <div class="form-group">
                    <label>Описание мастер-класса</label>
                    <textarea name="description" required rows="5">{{ old('description', $masterClass->description) }}</textarea>
                </div>
                
                <div class="form-group">
                    <label>Стоимость (руб.)</label>
                    <input type="number" step="0.01" name="price" value="{{ old('price', $masterClass->price) }}" min="0" required>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn">Сохранить изменения</button>
                    <a href="{{ route('cabinet') }}" style="margin-left: 15px;">Отмена</a>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="row row--nogutter"><div class="line"></div></div>
@endsection