<?php
// File: routes/channels.php

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Log;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
*/

// Channel untuk class
Broadcast::channel('class.{classId}', function ($user, $classId) {
    // Log untuk debugging
    Log::info('Broadcasting auth attempt', [
        'user_id' => $user->id,
        'user_class_id' => $user->class_id,
        'requested_class_id' => $classId,
        'has_access' => $user->class_id == $classId
    ]);

    // User hanya bisa akses channel kelasnya sendiri
    return (int) $user->class_id === (int) $classId;
});

// Channel untuk user pribadi (opsional)
Broadcast::channel('user.{userId}', function ($user, $userId) {
    return (int) $user->id === (int) $userId;
});
