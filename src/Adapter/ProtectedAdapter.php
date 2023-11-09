<?php

namespace FullscreenInteractive\SilverStripe\AzureStorage\Adapter;

use League\Flysystem\Config;
use League\Flysystem\FileAttributes;
use SilverStripe\Assets\Flysystem\ProtectedAdapter as SilverstripeProtectedAdapter;

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
        $dt = new \DateTime();

        if (is_string($this->getExpiry())) {
            $dt = $dt->setTimestamp(strtotime($this->getExpiry()));
        } else {
            $dt = $dt->setTimestamp(strtotime('+'.$this->getExpiry().' seconds'));
        }

        return $this->temporaryUrl($path, $dt, new Config());
    }
}
