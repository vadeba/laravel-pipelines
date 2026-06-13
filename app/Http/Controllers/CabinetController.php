<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class CabinetController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();

        if ($user->role === 'master') {
            $masterClasses = $user->createdMasterClasses()->with('participants')->orderBy('date')->orderBy('time_slot')->get();

            return view('cabinet.master', compact('user', 'masterClasses'));
        }

        $enrollments = $user->enrollments()->with(['master', 'type'])->get();

        return view('cabinet.user', compact('user', 'enrollments'));
    }
}
