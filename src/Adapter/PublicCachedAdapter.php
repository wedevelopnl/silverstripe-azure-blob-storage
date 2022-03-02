<?php

namespace FullscreenInteractive\SilverStripe\AzureStorage\Adapter;

use League\Flysystem\Cached\CachedAdapter;
use SilverStripe\Assets\Flysystem\PublicAdapter;

class PublicCachedAdapter extends CachedAdapter implements PublicAdapter
{
    public function getPublicUrl($path)
    {
        $url = $this->getAdapter()->getPublicUrl($path);

        return $url;
    }
}
