<?php

namespace App\Http\Controllers;

use App\Models\MessageCapsule;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $messageCapsules = MessageCapsule::where('user_id', Auth::id())->get();

        return view('dashboard', compact('messageCapsules'));
    }
}
