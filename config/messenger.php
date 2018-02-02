<?php

return [

    'user_model' => App\Storage\User\User::class,

    'message_model' => App\Storage\Messenger\Message::class,

    'participant_model' => Cmgmyr\Messenger\Models\Participant::class,

    'thread_model' => App\Storage\Messenger\Thread::class, // Cmgmyr\Messenger\Models\Thread::class,

    /**
     * Define custom database table names - without prefixes.
     */
    'messages_table' => 'messenger_messages',

    'participants_table' => 'messenger_participants',
    
    'threads_table' => 'messenger_threads',    
];
