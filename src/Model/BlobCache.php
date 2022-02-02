<?php

namespace FullscreenInteractive\SilverStripe\AzureStorage\Model;

use DateTimeImmutable;
use Exception;
use SilverStripe\Core\ClassInfo;
use SilverStripe\ORM\DataObject;
use SilverStripe\Security\Security;

/**
 * @property string $Key
 * @property string $Contents JSON cache
 */
class BlobCache extends DataObject
{
    private static $table_name = 'Azure_BlobCache';

    /**
     * @var array
     */
    protected static $instances = [];

    private static $db = [
        'Key'      => 'Varchar(191)',
        'Contents' => 'Text',
    ];

    private static $indexes = [
        'UniqueKey' => [
            'type'    => 'unique',
            'columns' => ['Key'],
        ],
    ];

    /**
     * Get cache for key
     *
     * @param string $key
     * @param int $maxAge Max age
     * @return static
     * @throws Exception
     */
    public static function instance(string $key, int $maxAge): self
    {
        if (isset(static::$instances[$key])) {
            return static::$instances[$key];
        }

        // Query from DB, respecting max age
        /** @var self $cache */
        $cache = static::get()->filter('Key', $key)->first();
        if (!$cache) {
            // Create blank record
            $cache = new self();
            $cache->Key = $key;
            $cache->write();
        } elseif ($maxAge) {
            // Expire contents if older than maxAge
            $modified = new DateTimeImmutable($cache->LastEdited);
            $expires = (new DateTimeImmutable())
                ->sub(new \DateInterval("PT{$maxAge}S"));

            // Flush expired contents
            if ($modified < $expires) {
                $cache->Contents = null;
                $cache->write();
            }
        }

        // Return
        static::$instances[$key] = $cache;
        return $cache;
    }

    /**
     * Safely disable db cache if not built yet
     * @return bool
     */
    public static function ready(): bool
    {
        return Security::database_is_ready() && ClassInfo::hasTable('Azure_BlobCache');
    }
}
