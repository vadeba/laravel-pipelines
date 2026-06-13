@extends('layouts.app')
@section('title', $category->name)

@section('content')
<div class="main">
    <div class="row">
        <div class="hover"></div>
        <div class="title">{{ $category->name }}</div>
        <div class="row--small grid between">
            <div class="content">
                <img src="{{ asset('img/elifant.png') }}">
                <p>{{ $category->description }}</p>
            </div>
            <ul class="menu">
                @foreach($allCategories as $cat)
                    <li><a href="{{ route('category.show', $cat->id) }}">{{ $cat->name }}</a></li>
                @endforeach
            </ul>
        </div>

        <div class="row shedule">
            <div class="row--small">
                
                @if(session('success'))
                    <h3 style="color: green;">{{ session('success') }}</h3>
                @endif
                @if($errors->any())
                    <h3 style="color: red;">{{ $errors->first() }}</h3>
                @endif

                <h2>Расписание</h2>
                <div class="drivers">
                    @forelse($masterClasses as $mc)
                    <div class="driver grid">
                        <div class="driver-left grid">
                            <div class="driver-photo">
                                <img src="{{ asset('img/' . ($mc->master->photo ?? 'driver1.png')) }}">
                            </div>
                            <div class="driver-text">
                                <div class="driver-name">{{ $mc->master->name }}</div>
                                <div class="driver-desc">
                                    <b>{{ $mc->title }}</b><br>
                                    {{ $mc->description }}<br><br>
                                    Стоимость: {{ $mc->price }} руб. | Свободных мест: {{ $mc->free_seats }}
                                </div>
                            </div>
                        </div>
                        <div class="driver-right">
                            @auth
                                @if($mc->free_seats > 0)
                                    <a href="{{ route('enroll.confirm', $mc->id) }}" class="driver-btn btn" style="text-decoration: none; text-align: center;">Записаться</a>
                                @else
                                    <button class="driver-btn" disabled style="background:#ccc; border:none;">Мест нет</button>
                                @endif
                            @endauth
                            
                            <div class="driver-time">{{ \Carbon\Carbon::parse($mc->date)->translatedFormat('d F') }} <br>{{ $mc->time_slot }}</div>
                        </div>  
                    </div>
                    @empty
                        <p>На данный момент нет запланированных мастер-классов по этому виду творчества.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>  
</div>
@endsection