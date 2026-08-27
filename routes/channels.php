<?php

use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
*/

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

// Eventos de usuário (UserUpdatedEvent) — só quem pode listar usuários.
Broadcast::channel('user_updated', function ($user) {
    return (bool) $user->profile
        ?->abilities()
        ->where('slug', 'list_usuario')
        ->exists();
});
