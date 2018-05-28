<?php

return [

    /**
     * Outgoing Token
     *
     * The token specified when setting up the outgoing web hook
     * https://rocket.chat/docs/administrator-guides/integrations/
     */
    'token' => '',

    /**
     * Rocket Chat Base URL
     *
     * The url your Rocket Chat server answers to (used for API calls)
     */
    'endpoint' => '',

    /**
     * The user ID of the bot (to avoid infinite loops)
     */
    'user_id' =>  env('ROCKET_CHAT_USER_ID', ''),

    /**
     * Rocket Chat Both Auth
     *
     * Your bot username and password on your Rocket Chat server
     */
    'bot' => [
        'username' => env('ROCKET_CHAT_USERNAME', 'bot'),
        'password' => env('ROCKET_CHAT_PASSWORD', 'secret'),
    ],

    /**
     * Matching Keys
     *
     * The required keys in the payload to be a valid RocketChat request
     */
    'matchingKeys' => [
        'user_id', 'user_name', 'channel_id', 'text', 'message_id', 'timestamp', 'bot',
    ],

];
