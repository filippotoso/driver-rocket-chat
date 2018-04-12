<?php

return [

    /**
     * Incoming / Outgoing Tokens
     *
     * The tokens specified when setting up the incoming and outgoing web hooks
     * https://rocket.chat/docs/administrator-guides/integrations/
     */
    'tokens' => [
        'incoming' => '',
        'outgoing' => '',
    ],

    /**
     * Incoming Web Hook Endpoint
     *
     * The url you get after configuring your incoming web hook
     */
    'endpoint' => '',

    /**
     * Matching Keys
     *
     * The required keys in the payload to be a valid RocketChat request
     */
    'matchingKeys' => [
        'user_id', 'user_name', 'channel_id', 'channel_name', 'message_id', 'text', 'timestamp', 'bot',
    ],

];
