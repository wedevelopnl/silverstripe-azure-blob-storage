<?php

namespace FullscreenInteractive\SilverStripe\AzureStorage\Service;

use MicrosoftAzure\Storage\Blob\BlobRestProxy;

class BlobService
{
    private static $client;

    private static $connectionUrl;

    public static function clientForConnection($connectionUrl)
    {
        if (!self::$connectionUrl || self::$connectionUrl !== $connectionUrl) {
            self::$connectionUrl = $connectionUrl;
            self::$client = BlobRestProxy::createBlobService($connectionUrl);
        }

        return self::$client;
    }

    public static function getClient()
    {
        return self::$client;
    }
}
