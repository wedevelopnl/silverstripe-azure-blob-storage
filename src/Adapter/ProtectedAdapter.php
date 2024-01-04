<?php

namespace FullscreenInteractive\SilverStripe\AzureStorage\Adapter;

use DateTime;
use MicrosoftAzure\Storage\Blob\BlobSharedAccessSignatureHelper;
use MicrosoftAzure\Storage\Common\Internal\Resources;
use SilverStripe\Assets\Flysystem\ProtectedAdapter as SilverstripeProtectedAdapter;
use SilverStripe\Control\Controller;

class ProtectedAdapter extends AzureBlobStorageAdapter implements SilverstripeProtectedAdapter
{
    /**
     * Pre-signed request expiration time in seconds, or relative string
     *
     * @var int|string
     */
    protected $expiry = 3600;

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
    public function setExpiry($expiry): self
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
            $token = '?' . $this->presignToken($meta['path']);
            return Controller::join_links($this->assetDomain, $meta['path'], $token);
        }

        return '';
    }

    public function getVisibility($path): array
    {
        // Save an API call
        return [
            'path'       => $path,
            'visibility' => self::VISIBILITY_PRIVATE
        ];
    }

    /**
     * Build a presigned token with an expiry
     *
     * @param string $path
     * @return string
     */
    protected function presignToken(string $path): string
    {
        $sasHelper = new BlobSharedAccessSignatureHelper(
            $this->settings->getName(),
            $this->settings->getKey()
        );

        // Get expiry string
        $expiry = $this->getExpiry();
        if (is_int($expiry)) {
            $expiry = "+{$expiry} seconds";
        }
        $validFrom = new DateTime();
        $expiry = (new DateTime())->modify($expiry);

        // Build token
        return $sasHelper->generateBlobServiceSharedAccessSignatureToken(
            Resources::RESOURCE_TYPE_BLOB,
            $this->container . '/' . $this->applyPathPrefix($path),
            'r',
            $expiry,
            $validFrom
        );
    }
}
