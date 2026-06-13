@extends('layouts.app')

@section('title', 'Личный кабинет ведущего')

@section('body_class', 'dp')

@section('content')
<div class="main">
    <div class="row">
        <div class="hover"></div>
        <div class="title"></div>
        <div class="row--small grid between">
            <div class="content driver-page" style="width: 100%;">
                
                @if(session('success'))
                    <div style="color: green; margin-bottom: 15px; font-weight:bold;">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="driver-page-photo">
                    <img src="{{ asset('img/' . ($user->photo ?? 'default.png')) }}" alt="Фото ведущего">
                </div>  
                <div class="driver-page-name">{{ $user->name }}</div>
                <div class="driver-page-text">
                    <div class="driver-page-my">Мои мастер-классы</div>
                    
                    <table class="driver-page-table" style="width: 100%;">
                        <tbody>
                            @forelse($masterClasses as $mc)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($mc->date)->translatedFormat('d F Y') }} <br> {{ $mc->time_slot }}</td>
                                <td>
                                    <b>{{ $mc->title }} ({{ $mc->type->name }})</b>
                                    <a href="{{ route('master-class.edit', $mc->id) }}" style="font-size: 12px; color: blue; margin-left: 10px;">[Редактировать]</a>
                                    <p>Стоимость: {{ $mc->price }} руб. | Мест: {{ $mc->participants->count() }}/{{ $mc->max_seats }}</p>
                                    
                                    <p><b>Участники:</b></p>
                                    @forelse($mc->participants as $index => $participant)
                                        <p style="margin-bottom: 5px;">
                                            {{ $index + 1 }}. {{ $participant->name }} <br>
                                            email: {{ $participant->email }} <br>
                                            tel: {{ $participant->phone }}
                                        </p>
                                    @empty
                                        <p style="color:gray;">Пока никто не записался.</p>
                                    @endforelse
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="2">У вас пока нет добавленных мастер-классов.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="driver-page-btn-wrapper">
                    <a href="{{ route('master-class.create') }}" class="driver-page-btn btn" style="text-decoration:none;">
                        Добавить мастер-класс
                    </a>
                </div>
            </div>
        </div>
    </div>  
</div>
<div class="row row--nogutter"><div class="line"></div></div>
@endsection