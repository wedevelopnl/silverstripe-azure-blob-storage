<?php

namespace FullscreenInteractive\SilverStripe\AzureStorage\Adapter;

use FullscreenInteractive\SilverStripe\AzureStorage\Service\BlobService;
use InvalidArgumentException;
use League\Flysystem\AzureBlobStorage\AzureBlobStorageAdapter;
use SilverStripe\Assets\Flysystem\PublicAdapter as SilverstripePublicAdapter;
use SilverStripe\Control\Controller;

class PublicAdapter extends AzureBlobStorageAdapter implements SilverstripePublicAdapter
{
    private $assetDomain;

    public function __construct($connectionUrl = '', $containerName = '', $assetDomain = '')
    {
        if (!$connectionUrl) {
            throw new InvalidArgumentException("AZURE_CONNECTION_URL environment variable not set");
        }

        if (!$containerName) {
            throw new InvalidArgumentException("AZURE_CONTAINER_NAME environment variable not set");
        }

        $client = BlobService::clientForConnection($connectionUrl);

        if ($assetDomain) {
            $this->assetDomain = $assetDomain;
        } else {
            $this->assetDomain = (string) BlobService::getClient()
                ->getPsrPrimaryUri()
                ->withPath($containerName);
        }

        parent::__construct($client, $containerName);
    }

    /**
     * @param string $path
     *
     * @return string
     */
    public function getPublicUrl($path)
    {
        $parts = explode('/', $path);

        if (isset($parts[0]) && $parts[0] === ASSETS_DIR) {
            array_shift($parts);
        }

        $path = implode('/', $parts);

        return Controller::join_links($this->assetDomain, $path);
    }
}
