<?php

namespace App\Http\Controllers;

use App\Models\CreativityType;
use App\Models\MasterClass;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class MasterClassController extends Controller
{
    public function create(): View|RedirectResponse
    {
        if (Auth::user()->role !== 'master') {
            return redirect('/cabinet')->withErrors('У вас нет прав для добавления мастер-класса.');
        }

        $types = CreativityType::all();

        return view('master_classes.create', compact('types'));
    }

    public function store(Request $request): RedirectResponse
    {
        if (Auth::user()->role !== 'master') {
            abort(403);
        }

        $request->validate([
            'type_id' => 'required|exists:creativity_types,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'date' => 'required|date|after_or_equal:today',
            'time_slot' => 'required|in:09:00,11:00,13:00,15:00',
            'max_seats' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
        ], [
            'price.min' => 'Стоимость не может быть отрицательной.',
            'price.numeric' => 'Стоимость должна быть числом.',
            'date.after_or_equal' => 'Дата мастер-класса не может быть в прошлом.',
            'max_seats.min' => 'Группа должна состоять минимум из 1 человека.',
        ]);

        $isOccupied = MasterClass::where('master_id', Auth::id())
            ->where('date', $request->date)
            ->where('time_slot', $request->time_slot)
            ->exists();

        if ($isOccupied) {
            return back()->withErrors('Это время на выбранную дату уже занято вами!')->withInput();
        }

        MasterClass::create([
            'master_id' => Auth::id(),
            'type_id' => $request->type_id,
            'title' => $request->title,
            'description' => $request->description,
            'date' => $request->date,
            'time_slot' => $request->time_slot,
            'max_seats' => $request->max_seats,
            'price' => $request->price,
        ]);

        return redirect()->route('cabinet')->with('success', 'Мастер-класс успешно добавлен!');
    }

    public function getOccupiedSlots(Request $request): JsonResponse
    {
        $date = $request->get('date');
        $master_id = Auth::id();

        $occupiedSlots = MasterClass::where('master_id', $master_id)
            ->where('date', $date)
            ->pluck('time_slot');

        return response()->json($occupiedSlots);
    }

    public function edit(int $id): View
    {
        $masterClass = MasterClass::findOrFail($id);

        if ($masterClass->master_id !== Auth::id()) {
            abort(403, 'У вас нет прав для редактирования этого мастер-класса.');
        }

        return view('master_classes.edit', compact('masterClass'));
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $masterClass = MasterClass::findOrFail($id);

        if ($masterClass->master_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
        ]);

        $masterClass->update([
            'description' => $request->description,
            'price' => $request->price,
        ]);

        return redirect()->route('cabinet')->with('success', 'Мастер-класс успешно обновлен!');
    }
}
