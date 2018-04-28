<?php

namespace FilippoToso\BotMan\Drivers\RocketChat;

use GuzzleHttp\Client;

class RocketChatAuth
{

    public static function checkAuth($userId, $authToken, $endpoint)
    {

        $url = str_finish($endpoint, '/') . 'api/v1/me';

        $client = new Client();

        $response = $client->request('GET', $url, [
            'headers' => [
                'Content-Type' => 'application/json',
                'X-User-Id' => $userId,
                'X-Auth-Token' => $authToken,
            ],
        ]);

        $data = json_decode((string) $response->getBody(), true);

        return isset($data['success']) && $data['success'];

    }

    public static function getAuth($username, $password, $endpoint)
    {

        $url = str_finish($endpoint, '/') . 'api/v1/login';

        $client = new Client();

        $response = $client->request('POST', $url, [
            'headers' => [
                'Content-Type' => 'application/json',
            ],
            'json' => [
                'username' => $username,
                'password' => $password,
            ],
        ]);

        $data = json_decode((string) $response->getBody(), true);

        if (isset($data['status']) && ($data['status'] == 'success')) {

            return [
                'user_id' => $data['data']['userId'],
                'token' => $data['data']['authToken'],
            ];

        }

        return false;

    }

}
