<?php

namespace FilippoToso\BotMan\Drivers\RocketChat;

use BotMan\BotMan\Drivers\HttpDriver;
use BotMan\BotMan\Users\User;
use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use BotMan\BotMan\Messages\Incoming\IncomingMessage;
use BotMan\BotMan\Messages\Outgoing\OutgoingMessage;
use BotMan\BotMan\Messages\Incoming\Answer;

class RocketChatDriver extends HttpDriver
{

    // TODO: Implement login + caching of user_id and auth_token

    const DRIVER_NAME = 'RocketChat';

    /** @var array */
    protected $messages = [];

    /** @var array */
    protected $files = [];

    /**
     * @param Request $request
     */
    public function buildPayload(Request $request)
    {
        $this->payload = $request->request->all();
        $this->event = Collection::make($this->payload);
        $this->files = Collection::make($request->files->all());
        $this->config = Collection::make($this->config->get('rocketchat', []));
    }

    /**
     * Verify that the request is valid agains the configured token
     * @method isValidRequest
     * @return boolean
     */
    protected function isValidRequest()
    {
        return !empty($this->config->get('token')) && ($this->event->get('token') == $this->config->get('token'));
    }

    /**
     * Determine if the request is for this driver.
     *
     * @return bool
     */
    public function matchesRequest()
    {
        return ! is_null($this->config->get('matchingKeys')) && Collection::make($this->config->get('matchingKeys'))->diff($this->event->keys())->isEmpty() && $this->isValidRequest();
    }

    /**
     * @return bool
     */
     public function isConfigured()
     {
         return ! empty($this->config->get('token')) && ! empty($this->config->get('endpoint')) && ! empty($this->config->get('matchingKeys'));
     }

    /**
     * Low-level method to perform driver specific API requests.
     *
     * @param string $endpoint
     * @param array $parameters
     * @param \BotMan\BotMan\Messages\Incoming\IncomingMessage $matchingMessage
     * @return void
     */
    public function sendRequest($endpoint, array $parameters, IncomingMessage $matchingMessage)
    {
        // Not available with the rocketchat driver.
    }

    /**
    * Retrieve the chat message.
    *
    * @return array
    */
    public function getMessages()
    {
        if (empty($this->messages)) {
            $text = $this->event->get('text');
            $userId = $this->event->get('user_id');
            $message = new IncomingMessage($text, $userId, $userId, $this->payload);
            $message->setIsFromBot((bool)$this->event->get('bot'));
            $this->messages = [$message];
        }
        return $this->messages;
    }

    /**
    * Retrieve User information.
    * @param IncomingMessage $matchingMessage
    * @return UserInterface
    */
    public function getUser(IncomingMessage $matchingMessage)
    {
        $payload = Collection::make($matchingMessage->getPayload());
        $userId = $payload->get('user_id');
        $username = $payload->get('user_name');
        return new User($userId, null, null, $username);
    }

    /**
    * @param IncomingMessage $message
    * @return \BotMan\BotMan\Messages\Incoming\Answer
    */
    public function getConversationAnswer(IncomingMessage $message)
    {
        return Answer::create($message->getText())->setMessage($message);
    }

    /**
    * @param string|Question|OutgoingMessage $message
    * @param IncomingMessage $matchingMessage
    * @param array $additionalParameters
    * @return Response
    */
    public function buildServicePayload($message, $matchingMessage, $additionalParameters = [])
    {
        if (! $message instanceof WebAccess && ! $message instanceof OutgoingMessage) {
            $this->errorMessage = 'Unsupported message type.';
            $this->replyStatusCode = 500;
        }

        $payload = Collection::make($matchingMessage->getPayload());

        if (is_null($payload->get('channel_name'))) {

            // Direct Message answer
            return [
                'text' => $message->getText(),
                'roomId' => $payload->get('user_id'),
            ];

        }

        // Channel answer
        return [
            'text' => $message->getText(),
            'roomId' => $payload->get('channel_id'),
        ];

    }

    /**
    * @param mixed $payload
    * @return Response
    */
    public function sendPayload($payload)
    {

        $url = str_finish($this->config->get('endpoint'), '/') . 'api/v1/chat.postMessage';

        $response = $this->http->post($url, [], $payload, [
            'Content-Type: application/json',
            'X-Auth-Token: ' . $this->config->get('auth')['token'],
            'X-User-Id: ' . $this->config->get('auth')['user_id'],
        ], true);

        return $response;

    }

}
