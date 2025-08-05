<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('class.{classId}', function ($user, $classId) {
    return $user->hasRole('siswa') && (int) $user->class_id === (int) $classId;
});
