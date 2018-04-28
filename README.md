# BotMan Rocket Chat Driver

BotMan driver to create bots for [RocketChat](https://rocket.chat/)

# Installation & Setup

First you need to pull in the RocketChat Driver.

```
composer require filippo-toso/driver-rocket-chat
```

Then load the driver before creating the BotMan instance (only when you don't use BotMan Studio):

```
DriverManager::loadDriver(\FilippoToso\BotMan\Drivers\RocketChat\RocketChatDriver::class);

// Create BotMan instance
BotManFactory::create($config);
```

This driver requires a valid and secure URL in order to set up webhooks and receive events and information from the chat users. This means your application should be accessible through an HTTPS URL.

[ngrok](https://ngrok.com/) is a great tool to create such a public HTTPS URL for your local application. If you use Laravel Valet, you can create it with "valet share" as well.

To connect BotMan with your RocketChat server, you need to:

1. Create an integration for outgoing messages. You can find more details about this process in the [RocketChat documentation](https://rocket.chat/docs/administrator-guides/integrations/).
2. Create a bot user in RocketChat that will "talk" with the other users.

Once you have setup the integration and created the user, you need to configure the driver. Open the app/config/botman/rocketchat.php file and insert the required information in the token, endpoint and bot parameters. Don't touch the 'matchingKeys' array.

# Authentication

To send messages as the bot user the driver needs to authenticate with the [REST API](https://rocket.chat/docs/developer-guides/rest-api/authentication/login/). To avoid the need to re-authenticate each time the bot needs to send a message, you can use the RocketChatAuth support class to get the access token and save it for later use. Here is a sample code you can use as a starting point.

```
use Illuminate\Support\Facades\Cache;
use FilippoToso\BotMan\Drivers\RocketChat\RocketChatAuth;

$auth = Cache::remember('rocketchat.auth', 60 * 24 * 30, function () {
    return RocketChatAuth::getAuth(
        config('botman.rocketchat.bot.username'),
        config('botman.rocketchat.bot.password'),
        config('botman.rocketchat.endpoint')
    );
});

config(['botman.rocketchat.auth' => $auth]);
```

You must execute it before creating the BotMan instance. It checks if the auth details are saved in Laravel's cache. If they aren't, RocketChatAuth authenticates with the REST API and saves the access token and user id in the cache for later use. These details are also saved in the botman.rocketchat.auth configuration array where the driver searches for them.

# Supported Features

Currently this driver supports only text messages (for both questions and answers). I'm working on the attachment implementation. If you want to contribute, get in touch!
