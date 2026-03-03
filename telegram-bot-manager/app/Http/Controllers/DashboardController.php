<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bot;
use DefStudio\Telegraph\Telegraph;

class DashboardController extends Controller
{
    public function index()
    {
        $bots = auth()->user()->bots()->get();

        return view('dashboard', compact('bots'));
    }

    public function storeBot(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'token' => 'required|string|regex:/^[0-9]{8,10}:[a-zA-Z0-9_-]{35}$/',
        ]);

        $bot = auth()->user()->bots()->create([
            'name' => $validated['name'],
            'token' => $validated['token'],
        ]);

        // Регистрируем вебхук
        $bot->registerWebhook()->send();

        return redirect()->route('dashboard')->with('success', 'Бот успешно добавлен!');
    }
}
