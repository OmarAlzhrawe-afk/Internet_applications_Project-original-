<?php

use Illuminate\Support\Facades\Broadcast;

// Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
//     return true;
// });
// Broadcast::channel('user-register', function ($user) {
//     // return in_array($user->role, ['supervisor', 'admin']) && $user->isActive();
//     return true;
// });

// Broadcast::channel('App.Models.User', function ($user) {
//     // return in_array($user->role, ['supervisor', 'admin']) && $user->isActive();
//     return true;
// });
// Broadcast::channel('user.{id}', function ($user, $id) {
//     return (int) $user->id === (int) $id;
// });
Broadcast::channel('users.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});
