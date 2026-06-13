@extends('layouts.app')
@section('title', 'Главная страница')

@section('content')
<div class="row row--nogutter top-line"><div class="line"></div></div>
<div class="main">
    <div class="row">
        <div class="row--small">
            
            <h2>О компании «Очумелые ручки»</h2>
            <p>Добро пожаловать в клуб любителей творчества! Наша цель — объединить творческих людей и дать возможность учиться новому у профессиональных мастеров. Мы регулярно проводим уникальные мастер-классы для всех желающих.</p>
            
            <hr style="margin: 30px 0; border: none; border-top: 1px solid #ccc;">

            <h2>Виды творчества</h2>
            <ul class="menu" style="display: flex; flex-wrap: wrap; gap: 15px; list-style: none; padding: 0;">
                @foreach($categories as $cat)
                    <li style="background: #f0f0f0; padding: 10px 20px; border-radius: 5px;">
                        <a href="{{ route('category.show', $cat->id) }}" style="text-decoration: none; color: #333; font-weight: bold;">
                            {{ $cat->name }}
                        </a>
                    </li>
                @endforeach
            </ul>

            @auth
                @if(Auth::user()->role === 'user')
                    <hr style="margin: 30px 0; border: none; border-top: 1px solid #ccc;">
                    <h2>Мои записи на мастер-классы</h2>
                    
                    @if($enrollments->isEmpty())
                        <p>Вы пока не записались ни на один мастер-класс.</p>
                    @else
                        <table class="driver-page-table" style="width: 100%; text-align: left; border-collapse: collapse;">
                            <thead>
                                <tr>
                                    <th style="border-bottom: 2px solid #ccc; padding: 10px;">Дата и время</th>
                                    <th style="border-bottom: 2px solid #ccc; padding: 10px;">Название (Вид)</th>
                                    <th style="border-bottom: 2px solid #ccc; padding: 10px;">Ведущий</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($enrollments as $mc)
                                <tr>
                                    <td style="padding: 10px; border-bottom: 1px solid #eee;">
                                        {{ \Carbon\Carbon::parse($mc->date)->format('d.m.Y') }} <br> {{ $mc->time_slot }}
                                    </td>
                                    <td style="padding: 10px; border-bottom: 1px solid #eee;">
                                        <b>{{ $mc->title }}</b> <br> <span style="font-size: 0.9em; color: gray;">{{ $mc->type->name }}</span>
                                    </td>
                                    <td style="padding: 10px; border-bottom: 1px solid #eee;">
                                        {{ $mc->master->name }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                @endif
            @endauth

        </div>
    </div>
</div>
<div class="row row--nogutter"><div class="line"></div></div>
@endsection