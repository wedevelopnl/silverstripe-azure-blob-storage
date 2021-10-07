<?php

namespace FullscreenInteractive\SilverStripe\AzureStorage\Adapter;

use SilverStripe\Assets\Flysystem\PublicAdapter as SilverstripePublicAdapter;
use SilverStripe\Control\Controller;

class PublicAdapter extends AzureBlobStorageAdapter implements SilverstripePublicAdapter
{
    /**
     * @param string $path
     *
     * @return string
     */
    public function getPublicUrl($path): string
    {
        if ($meta = $this->getMetadata($path)) {
            return Controller::join_links($this->assetDomain, $meta['path']);
        }

        return '';
    }
}
