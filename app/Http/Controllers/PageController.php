<?php

namespace App\Http\Controllers;

use App\Models\CreativityType;
use App\Models\MasterClass;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class PageController extends Controller
{
    public function home(): View
    {
        $categories = CreativityType::all();
        $enrollments = [];

        if (Auth::check() && Auth::user()->role === 'user') {
            $enrollments = Auth::user()->enrollments()->with(['master', 'type'])->orderBy('date')->get();
        }

        return view('home', compact('categories', 'enrollments'));
    }

    public function category(int $id): View
    {
        $category = CreativityType::findOrFail($id);
        $allCategories = CreativityType::all();

        $masterClasses = MasterClass::with(['master', 'participants'])
            ->where('type_id', $id)
            ->where('date', '>=', now()->toDateString())
            ->orderBy('date')
            ->orderBy('time_slot')
            ->get();

        return view('category', compact('category', 'allCategories', 'masterClasses'));
    }
}
