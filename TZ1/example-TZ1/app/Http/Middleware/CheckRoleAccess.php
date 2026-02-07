<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRoleAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Получаем роль из заголовка
        $role = $request->header('X-User-Role');

        // Если роль не передана - запрещаем доступ
        if (!$role) {
            abort(403, 'Access denied. X-User-Role header is required.');
        }

        // Проверяем роль "admin" - доступ всегда разрешен
        if ($role === 'admin') {
            return $next($request);
        }

        // Проверяем роль "user" - доступ только в рабочее время
        if ($role === 'user') {
            // Получаем текущее время на сервере
            $currentHour = now()->hour;
            $currentMinute = now()->minute;

            // Проверяем рабочее время (09:00-18:00)
            // 9:00 = час 9, минута 0
            // 18:00 = час 18, минута 0
            $isWorkTime = false;

            // Если время между 9:00 и 18:00
            if ($currentHour > 9 && $currentHour < 18) {
                $isWorkTime = true;
            } elseif ($currentHour === 9 && $currentMinute >= 0) {
                // ровно 9 часов, минуты >= 0
                $isWorkTime = true;
            } elseif ($currentHour === 18 && $currentMinute === 0) {
                // ровно 18:00 - доступ есть
                $isWorkTime = true;
            }

            if ($isWorkTime) {
                return $next($request);
            }

            abort(403, 'Access denied. Users can only access during working hours (09:00-18:00).');
        }

        // Для всех других ролей - запрещаем доступ
        abort(403, 'Access denied. Invalid role.');
    }
}
