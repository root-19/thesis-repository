<?php

return [
    'messages.{id}' => function ($user, $id) {
        return (int) $user->id === (int) $id;
    },
];
