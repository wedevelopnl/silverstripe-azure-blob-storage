<?php

namespace FullscreenInteractive\SilverStripe\AzureStorage\Adapter;

use FullscreenInteractive\SilverStripe\AzureStorage\Service\BlobService;
use InvalidArgumentException;
use League\Flysystem\AzureBlobStorage\AzureBlobStorageAdapter;
use SilverStripe\Assets\Flysystem\ProtectedAdapter as SilverstripeProtectedAdapter;
use SilverStripe\Control\Controller;

class ProtectedAdapter extends AzureBlobStorageAdapter implements SilverstripeProtectedAdapter
{
    /**
     * Pre-signed request expiration time in seconds, or relative string
     *
     * @var int|string
     */
    protected $expiry = 300;

    public function __construct($connectionUrl = '', $containerName = '')
    {
        if (!$connectionUrl) {
            throw new InvalidArgumentException("AZURE_CONNECTION_URL environment variable not set");
        }

        if (!$containerName) {
            throw new InvalidArgumentException("AZURE_PROTECTED_CONTAINER_NAME environment variable not set");
        }

        $client = BlobService::clientForConnection($connectionUrl);

        parent::__construct($client, $containerName);
    }

    /**
     * @return int|string
     */
    public function getExpiry()
    {
        return $this->expiry;
    }

    /**
     * Set expiry. Supports either number of seconds (in int) or
     * a literal relative string.
     *
     * @param int|string $expiry
     * @return $this
     */
    public function setExpiry($expiry)
    {
        $this->expiry = $expiry;
        return $this;
    }

    /**
     * @param string $path
     *
     * @return string
     */
    public function getProtectedUrl($path)
    {
        if ($meta = $this->getMetadata($path)) {
            return Controller::join_links(ASSETS_DIR, $meta['path']);
        }

        return '';
    }

    public function getVisibility($path)
    {
        // Save an API call
        return [
            'path' => $path,
            'visibility' => self::VISIBILITY_PRIVATE
        ];
    }
}
