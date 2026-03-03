<?php

namespace App\Http\Controllers;

use App\Models\Bot;
use App\Models\Subscriber;
use App\Jobs\SendBroadcastJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BotController extends Controller
{
    public function show(Bot $bot)
    {
        // Проверяем, принадлежит ли бот текущему пользователю
        if ($bot->user_id !== Auth::id()) {
            abort(403);
        }

        $subscribers = $bot->subscribers()->paginate(20);

        return view('bots.show', compact('bot', 'subscribers'));
    }

    public function destroySubscriber(Bot $bot, Subscriber $subscriber)
    {
        if ($bot->id !== $subscriber->telegraph_bot_id || $bot->user_id !== Auth::id()) {
            abort(403);
        }

        $subscriber->delete();

        return redirect()->route('bots.show', $bot)
            ->with('success', 'Подписчик удален');
    }

    public function broadcast(Request $request, Bot $bot)
    {
        if ($bot->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'message' => 'required_without:file|string|max:4096',
            'file' => 'nullable|file|max:10240', // макс 10MB
        ]);

        SendBroadcastJob::dispatch(
            $bot,
            $validated['message'] ?? '',
            $request->file('file')
        );

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Рассылка запущена']);
        }

        return redirect()->route('bots.show', $bot)
            ->with('success', 'Рассылка запущена');
    }
}
