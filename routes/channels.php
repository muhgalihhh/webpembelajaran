<?php
// File: routes/channels.php

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Log;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
*/

Broadcast::channel('class.{classId}', function ($user, $classId) {

    return (int) $user->class_id === (int) $classId;
});
// Channel untuk user pribadi (opsional)
Broadcast::channel('user.{userId}', function ($user, $userId) {
    return (int) $user->id === (int) $userId;
});
