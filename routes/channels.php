<?php

use App\Models\CoreEngine\ProjectModels\Chat\ChatUser;
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

//Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
//    return (int) $user->id === (int) $id;
//});

Broadcast::channel('send_message.{chatId}', function ($user, $chatId) {
    return ChatUser::where([['user_id', $user->id], ['chat_id', $chatId], ['is_deleted', 0], ['is_block', 0]])->exists();
});

Broadcast::channel('notification_user.{userId}', function ($user, $userId) {
    return $user->id == $userId;
});
