<?php

namespace FullscreenInteractive\SilverStripe\AzureStorage\Adapter;

use FullscreenInteractive\SilverStripe\AzureStorage\Service\BlobService;
use InvalidArgumentException;
use League\Flysystem\AzureBlobStorage\AzureBlobStorageAdapter as BaseAdapter;
use League\Flysystem\Config;
use MicrosoftAzure\Storage\Common\Internal\StorageServiceSettings;

abstract class AzureBlobStorageAdapter extends BaseAdapter
{
    /**
     * @var string
     */
    protected $assetDomain = null;

    /**
     * @var StorageServiceSettings
     */
    protected $container = null;

    /**
     * @var StorageServiceSettings|null
     */
    protected $settings = null;

    public function __construct($connectionUrl = '', $containerName = '', $assetDomain = '', $pathPrefix = null)
    {
        if (!$connectionUrl) {
            throw new InvalidArgumentException("AZURE_CONNECTION_URL environment variable not set");
        }

        if (!$containerName) {
            throw new InvalidArgumentException("AZURE_CONTAINER_NAME environment variable not set");
        }

        // Store settings
        $this->container = $containerName;
        $this->settings = StorageServiceSettings::createFromConnectionString($connectionUrl);

        // Generate client
        $client = BlobService::clientForConnection($connectionUrl);

        // Determine url
        if ($assetDomain) {
            $this->assetDomain = $assetDomain;
        } else {
            $this->assetDomain = $client
                ->getPsrPrimaryUri()
                ->withPath('/' . $containerName)
                ->__toString();
        }

        parent::__construct($client, $containerName, $pathPrefix);
    }

    public function writeStream($path, $resource, Config $config)
    {
        if (is_resource($resource) && !\stream_get_meta_data($resource)['seekable']) {
            $resource = $this->makeStreamSeekable($resource);
        }

        return parent::writeStream($path, $resource, $config);
    }

    private function makeStreamSeekable($resource)
    {
        $seekableStream = fopen('php://temp', 'w+b');

        if ($seekableStream === false) {
            throw new \Exception("Failed to open temporary stream");
        }

        stream_copy_to_stream($resource, $seekableStream);
        rewind($seekableStream);
        fclose($resource);

        return $seekableStream;
    }
}
