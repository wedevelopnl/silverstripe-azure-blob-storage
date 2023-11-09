<?php

namespace FullscreenInteractive\SilverStripe\AzureStorage\Adapter;

use FullscreenInteractive\SilverStripe\AzureStorage\Service\BlobService;
use InvalidArgumentException;
use League\Flysystem\AzureBlobStorage\AzureBlobStorageAdapter as BaseAdapter;
use League\Flysystem\FileAttributes;
use MicrosoftAzure\Storage\Common\Internal\StorageServiceSettings;

abstract class AzureBlobStorageAdapter extends BaseAdapter
{
    public function __construct(string $connectionUrl = '', string $containerName = '', ?string $prefix = '', ?int $maxResultsForContentsListing = 5000)
    {
        if (!$connectionUrl) {
            throw new InvalidArgumentException("AZURE_CONNECTION_URL environment variable not set");
        }

        if (!$containerName) {
            throw new InvalidArgumentException(
                "AZURE_CONTAINER_NAME or AZURE_PROTECTED_CONTAINER_NAME environment variable not set"
            );
        }

        $settings = StorageServiceSettings::createFromConnectionString($connectionUrl);

        // Generate client
        $client = BlobService::clientForConnection($connectionUrl);

        parent::__construct(
            $client,
            $containerName,
            $prefix ?? '',
            null,
            $maxResultsForContentsListing,
            self::ON_VISIBILITY_IGNORE,
            $settings
        );
    }


    public function getMetadata($path): array
    {
        debug_backtrace();

        return [];
    }



    public function visibility($path): FileAttributes
    {
        return new FileAttributes($path, null, 'public');
    }

    public function setVisibility($path, $visibility): void
    {
        // no op
    }
}
