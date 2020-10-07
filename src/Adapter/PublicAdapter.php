<?php

namespace FullscreenInteractive\SilverStripe\AzureStorage\Adapter;

use InvalidArgumentException;
use League\Flysystem\AzureBlobStorage\AzureBlobStorageAdapter;
use MicrosoftAzure\Storage\Blob\BlobRestProxy;
use SilverStripe\Assets\Flysystem\PublicAdapter as SilverstripePublicAdapter;

class PublicAdapter extends AzureBlobStorageAdapter implements SilverstripePublicAdapter
{
    public function __construct($connectionUrl = '', $containerName = '')
    {
        if (!$connectionUrl) {
            throw new InvalidArgumentException("AZURE_CONNECTION_URL environment variable not set");
        }

        if (!$containerName) {
            throw new InvalidArgumentException("AZURE_CONTAINER_NAME environment variable not set");
        }

        $client = BlobRestProxy::createBlobService($connectionUrl);

        parent::__construct($client, $containerName);
    }

    /**
     * @param string $path
     *
     * @return string
     */
    public function getPublicUrl($path)
    {
        if ($meta = $this->getMetadata($path)) {
            return $meta['url'];
        }

        return '';
    }
}
