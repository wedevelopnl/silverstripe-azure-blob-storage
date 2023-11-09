<?php

namespace FullscreenInteractive\SilverStripe\AzureStorage\Adapter;

use League\Flysystem\Config;
use SilverStripe\Assets\Flysystem\PublicAdapter as SilverstripePublicAdapter;

class PublicAdapter extends AzureBlobStorageAdapter implements SilverstripePublicAdapter
{
    /**
     * @param string $path
     *
     * @return string
     */
    public function getPublicUrl($path): string
    {
        return $this->publicUrl($path, new Config());
    }
}
