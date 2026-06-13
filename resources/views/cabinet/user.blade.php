@extends('layouts.app')
@section('title', 'Личный кабинет пользователя')
@section('body_class', 'dp')

@section('content')
<div class="main">
    <div class="row">
        <div class="hover"></div>
        <div class="title"></div>
        <div class="row--small grid between">
            <div class="content driver-page" style="width: 100%;">
                <div class="driver-page-name">Здравствуйте, {{ $user->name }}!</div>
                <div class="driver-page-text">
                    <div class="driver-page-my">Вы записаны на следующие мастер-классы:</div>
                    
                    <table class="driver-page-table" style="width: 100%;">
                        <tbody>
                            @forelse($enrollments as $mc)
                            <tr>
                                <td style="width: 20%;">{{ \Carbon\Carbon::parse($mc->date)->format('d.m.Y') }} <br> {{ $mc->time_slot }}</td>
                                <td>
                                    <b>{{ $mc->title }}</b> ({{ $mc->type->name }})<br>
                                    Ведущий: {{ $mc->master->name }}<br>
                                    Стоимость: {{ $mc->price }} руб.
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="2">Вы пока никуда не записаны.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>  
</div>
<div class="row row--nogutter"><div class="line"></div></div>
@endsection