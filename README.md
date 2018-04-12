# BotMan Rocket Chat Driver

BotMan driver to create bots for [RocketChat](https://rocket.chat/)

# Installation & Setup

First you need to pull in the Telegram Driver.

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

To connect BotMan with your RocketChat server, you need to create 2 integrations: one for incoming messages and one for outcoming ones. You can find more details about this process in the [RocketChat documentation](https://rocket.chat/docs/administrator-guides/integrations/).

Once you have setup the integrations, you need to configure the driver. Open the app/config/botman/rocketchat.php file and insert the required information in the tokens and endpoint parameters. Don't touch the 'matchingKeys' array.

# Supported Features

Currently this driver supports only text messages (for both questions and answers). I'm working on the attachment implementation. If you want to contribute, get in touch!
