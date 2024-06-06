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

        return  $fileContent;
    }
}
