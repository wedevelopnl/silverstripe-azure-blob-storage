<?php

namespace FullscreenInteractive\SilverStripe\AzureStorage\Adapter;

use Exception;
use FullscreenInteractive\SilverStripe\AzureStorage\Model\BlobCache;
use League\Flysystem\Cached\Storage\AbstractCache;

class DBCache extends AbstractCache
{
    /**
     * Cache key
     *
     * @var string|null
     */
    protected $key = null;

    /**
     * @var int|null seconds until cache expiration
     */
    protected $expire = null;

    protected $ready = false;

    /**
     * Constructor.
     *
     * @param string $key storage key
     * @param int|null $expire
     */
    public function __construct(string $key = 'flysystem', int $expire = null)
    {
        $this->key = $key;
        $this->expire = $expire;
        $this->ready = BlobCache::ready();
    }

    /**
     * @throws Exception
     */
    public function load()
    {
        if (!$this->ready) {
            return;
        }

        $cache = BlobCache::instance($this->key, $this->expire);
        $contents = $cache->Contents;

        if ($contents) {
            $this->setFromStorage($contents);
        }
    }

    /**
     * @throws Exception
     */
    public function save()
    {
        if (!$this->ready) {
            return;
        }

        $contents = $this->getForStorage();
        $blobCache = BlobCache::instance($this->key, $this->expire);
        $blobCache->Contents = $contents;
        $blobCache->write();
    }
}
