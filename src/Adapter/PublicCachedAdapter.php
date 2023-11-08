<?php

namespace FullscreenInteractive\SilverStripe\AzureStorage\Adapter;

use jgivoni\Flysystem\Cache\CacheAdapter;
use SilverStripe\Assets\Flysystem\PublicAdapter;

class PublicCachedAdapter extends CacheAdapter implements PublicAdapter
{
    public function getPublicUrl($path)
    {
        $url = $this->getAdapter()->getPublicUrl($path);

        return $url;
    }

    public function getAdapter()
    {
        return $this->adapter;
    }
}
