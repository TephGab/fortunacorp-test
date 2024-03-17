<?php

use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('useronline', function ($user) {
    return ['name'=>$user->name];
});

Broadcast::channel('transaction', function ($user) {
    return ['name'=>$user->name];
});

Broadcast::channel('new-transaction', function ($user) {
    return ['name'=>$user->name];
});

Broadcast::channel('cashout', function ($user) {
    return ['name'=>$user->name];
});

Broadcast::channel('agentdeposit', function ($user) {
    return ['name'=>$user->first_name];
});

Broadcast::channel('envoydeposit', function ($user) {
    return ['name'=>$user->first_name];
});

Broadcast::channel('envoytransfert', function ($user) {
    return ['name'=>$user->first_name];
});

Broadcast::channel('replenishment', function ($user) {
    return ['name'=>$user->name];
});