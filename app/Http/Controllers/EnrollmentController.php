<?php

namespace App\Http\Controllers;

use App\Models\MasterClass;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class EnrollmentController extends Controller
{
    public function confirm(int $id): View
    {
        $masterClass = MasterClass::with(['master', 'type'])->findOrFail($id);
        $user = Auth::user();

        return view('enrollments.confirm', compact('masterClass', 'user'));
    }

    public function store(int $id): RedirectResponse
    {
        $masterClass = MasterClass::findOrFail($id);
        $user = Auth::user();

        if ($user->enrollments()->where('master_class_id', $id)->exists()) {
            return redirect()->route('category.show', $masterClass->type_id)
                ->withErrors('Вы уже записаны на этот мастер-класс.');
        }

        if ($masterClass->free_seats <= 0) {
            return redirect()->route('category.show', $masterClass->type_id)
                ->withErrors('К сожалению, свободных мест больше нет.');
        }

        $user->enrollments()->attach($masterClass->id);

        return redirect()->route('category.show', $masterClass->type_id)
            ->with('success', 'Вы успешно записались на мастер-класс!');
    }
}
