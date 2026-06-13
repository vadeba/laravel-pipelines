@extends('layouts.app')

@section('title', 'Добавление мастер-класса')

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

            <form action="{{ route('master-class.store') }}" method="POST">
                @csrf
                <h2>Форма добавления мастер-класса</h2>
                
                <div class="form-group">
                    <label>Вид творчества</label>
                    <select name="type_id" required>
                        <option value="" disabled selected>Выберите вид</option>
                        @foreach($types as $type)
                            <option value="{{ $type->id }}" {{ old('type_id') == $type->id ? 'selected' : '' }}>
                                {{ $type->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>Название мастер-класса</label>
                    <input type="text" name="title" value="{{ old('title') }}" required>
                </div>

                <div class="form-group">
                    <label>Описание мастер-класса</label>
                    <textarea name="description" required>{{ old('description') }}</textarea>
                </div>
                
                <div class="form-group">
                    <label>Дата</label>
                    <input type="date" name="date" id="mc-date" value="{{ old('date') }}" required min="{{ date('Y-m-d') }}">
                </div>
                
                <div class="form-group">
                    <label>Время (Сетка занятий: 2 часа)</label>
                    <select name="time_slot" id="mc-time" required>
                        <option value="" disabled selected>Сначала выберите дату</option>
                        <option value="09:00">09:00 - 11:00</option>
                        <option value="11:00">11:00 - 13:00</option>
                        <option value="13:00">13:00 - 15:00</option>
                        <option value="15:00">15:00 - 17:00</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Количество человек в группе</label>
                    <input type="number" name="max_seats" value="{{ old('max_seats') }}" min="1" required>
                </div>

                <div class="form-group">
                    <label>Стоимость (руб.)</label>
                    <input type="number" step="0.01" name="price" value="{{ old('price') }}" min="0" required>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn">Сохранить</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="row row--nogutter"><div class="line"></div></div>

<script>
document.getElementById('mc-date').addEventListener('change', function() {
    const date = this.value;
    const timeSelect = document.getElementById('mc-time');
    const options = timeSelect.querySelectorAll('option[value!=""]');

    if (!date) return;

    options.forEach(opt => {
        opt.disabled = false;
        opt.innerHTML = opt.value + ' - ' + (parseInt(opt.value) + 2) + ':00';
    });

    fetch(`/api/occupied-slots?date=${date}`)
        .then(response => response.json())
        .then(occupiedSlots => {
            options.forEach(opt => {
                if (occupiedSlots.includes(opt.value)) {
                    opt.disabled = true;
                    opt.innerHTML += ' (Занято)';
                }
            });
            if(timeSelect.selectedOptions[0].disabled) {
                timeSelect.value = "";
            }
        });
});
</script>
@endsection