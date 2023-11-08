<?php

namespace FullscreenInteractive\SilverStripe\AzureStorage\Adapter;

use jgivoni\Flysystem\Cache\CacheAdapter;
use SilverStripe\Assets\Flysystem\ProtectedAdapter;

class ProtectedCachedAdapter extends CacheAdapter implements ProtectedAdapter
{
    public function getProtectedUrl($path)
    {
        return $this->getAdapter()->getProtectedUrl($path);
    }

    public function getAdapter()
    {
        return $this->adapter;
    }
}
