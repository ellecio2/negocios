<?php

namespace App\Http\Controllers\Workshop;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MarkAsReadAndRedirectController extends Controller
{
    public function markAsReadAndRedirect()
    {
        // Ejecutar el código que marca las notificaciones como leídas, pero las marca de todos, tengo que buscar la forma que se marken todas pero del usuario actual
        // auth()->user()->unreadNotifications->markAsRead();
        // Redirigir a la ruta deseada
        return redirect()->route('workshop.workshopService.index');
    }
}
