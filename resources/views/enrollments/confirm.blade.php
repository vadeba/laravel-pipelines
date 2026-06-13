@extends('layouts.app')
@section('title', 'Подтверждение записи')

@section('content')
<div class="row row--nogutter top-line"><div class="line"></div></div>
<div class="main">
    <div class="row">
        <div class="row--small">
            <h2>Подтверждение записи</h2>
            <div style="background: #f9f9f9; padding: 20px; border-radius: 5px;">
                <p><b>Участник (ФИО):</b> {{ $user->name }}</p>
                <p><b>Вид творчества:</b> {{ $masterClass->type->name }}</p>
                <p><b>Мастер-класс:</b> {{ $masterClass->title }}</p>
                <p><b>Ведущий:</b> {{ $masterClass->master->name }}</p>
                <p><b>Дата и время:</b> {{ \Carbon\Carbon::parse($masterClass->date)->format('d.m.Y') }} в {{ $masterClass->time_slot }}</p>
                <p><b>Стоимость:</b> {{ $masterClass->price }} руб.</p>
                
                <form action="{{ route('enroll.store', $masterClass->id) }}" method="POST" style="margin-top: 20px;">
                    @csrf
                    <button type="submit" class="btn" style="cursor: pointer;">Подтвердить запись</button>
                    <a href="{{ route('category.show', $masterClass->type_id) }}" class="btn" style="background:#ccc; color:#000; text-decoration:none; margin-left:10px;">Отмена</a>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="row row--nogutter"><div class="line"></div></div>
@endsection