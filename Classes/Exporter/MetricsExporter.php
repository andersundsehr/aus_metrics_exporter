<?php

declare(strict_types=1);

namespace AUS\AusMetricsExporter\Exporter;

use AUS\AusMetricsExporter\Service\CollectorService;

class MetricsExporter
{
    public function __construct(
        protected readonly CollectorService $collectorService,
    ) {
    }

    public function export(): string
    {
        $fileContent = '';
        foreach ($this->collectorService->fetch() as $identifier => $value) {
            $fileContent .= $identifier . ' ' . $value . PHP_EOL;
        }

        return $fileContent . $this->generateApcuMetrics();
    }

    /**
     * Generate Prometheus metrics from APCu cache information.
     *
     * @return string|null The metrics in Prometheus exposition format or null if APCu is not available.
     */
    protected function generateApcuMetrics(): ?string
    {
        if (!function_exists('apcu_cache_info') || !extension_loaded('apcu')) {
            return null;
        }

        /** @var array<string, int> $cacheInfo */
        $cacheInfo = apcu_cache_info();
        $smaInfo = function_exists('apc_sma_info') ? apc_sma_info() : null;

        $metrics = [];

        $metrics[] = '# TYPE apcu_cache_num_entries gauge';
        $metrics[] = 'apcu_cache_num_entries ' . (int)$cacheInfo['num_entries'];

        $metrics[] = '# TYPE apcu_cache_mem_size gauge';
        $metrics[] = 'apcu_cache_mem_size ' . (int)$cacheInfo['mem_size'];

        $metrics[] = '# TYPE apcu_cache_hits counter';
        $metrics[] = 'apcu_cache_hits ' . (int)$cacheInfo['num_hits'];

        $metrics[] = '# TYPE apcu_cache_misses counter';
        $metrics[] = 'apcu_cache_misses ' . (int)$cacheInfo['num_misses'];

        $metrics[] = '# TYPE apcu_cache_inserts counter';
        $metrics[] = 'apcu_cache_inserts ' . (int)$cacheInfo['num_inserts'];

        $metrics[] = '# TYPE apcu_cache_expunges counter';
        $metrics[] = 'apcu_cache_expunges ' . (int)$cacheInfo['num_expunges'];

        // Add shared memory allocation info if available
        if ($smaInfo) {
            $metrics[] = '# TYPE apcu_sma_seg_size gauge';
            $metrics[] = 'apcu_sma_seg_size ' . (int)$smaInfo['seg_size'];

            $metrics[] = '# TYPE apcu_sma_avail_mem gauge';
            $metrics[] = 'apcu_sma_avail_mem ' . (int)$smaInfo['avail_mem'];
        }

        $metrics[] = '# TYPE apcu_shm_size';
        $metrics[] = 'apcu_shm_size ' . ini_get('apc.shm_size');
        $metrics[] = '# TYPE memory_limit';
        $metrics[] = 'memory_limit ' . ini_get('memory_limit');

        return implode(PHP_EOL, $metrics) . PHP_EOL;
    }
}
