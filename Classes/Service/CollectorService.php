<?php

declare(strict_types=1);

namespace AUS\AusMetricsExporter\Service;

use AUS\AusMetricsExporter\Exception\MetricsException;
use TYPO3\CMS\Core\Cache\CacheManager;
use TYPO3\CMS\Core\Cache\Exception\NoSuchCacheException;
use TYPO3\CMS\Core\Cache\Frontend\VariableFrontend;
use TYPO3\CMS\Core\SingletonInterface;

class CollectorService implements SingletonInterface
{
    protected VariableFrontend $cache;

    /**
     * @throws NoSuchCacheException
     */
    public function __construct(protected readonly CacheManager $cacheManager)
    {
        $cache = $this->cacheManager->getCache('ausmetricsexporter_cache');
        if (!$cache instanceof VariableFrontend) {
            throw new MetricsException('ausmetricsexporter_cache must implement VariableFrontend');
        }

        $this->cache = $cache;
    }

    public function collect(string $key, string $value, bool $set = false): void
    {
        $keys = $this->getKeys();
        $keys = array_flip($keys);
        $keys[$key] = true;
        $this->cache->set('_keys', array_keys($keys));

        if ($set || !$this->cache->has($key)) {
            $this->cache->set($key, $value);
            return;
        }

        $currentValue = $this->cache->get($key);
        if (!is_string($currentValue)) {
            $currentValue = '0.0';
        }

        $added = bcadd($value, $currentValue, 16);
        $this->cache->set($key, $added);
    }

    /**
     * @return array<string, string>
     */
    public function fetch(): array
    {
        $values = [];
        foreach ($this->getKeys() as $key) {
            $value = $this->cache->get($key);
            if (!is_string($value)) {
                continue;
            }

            $values[$key] = $value;
        }

        return $values;
    }

    /**
     * @return array<string>
     */
    protected function getKeys(): array
    {
        $keys = $this->cache->get('_keys');
        if (!is_array($keys)) {
            return [];
        }

        return $keys;
    }
}
