<?php

declare(strict_types=1);

namespace AUS\MetricsExporter\Event;

use AUS\MetricsExporter\Service\CollectorService;
use Prometheus\CollectorRegistry;

class BeforeMetricsRenderEvent
{
    public function __construct(private readonly CollectorService $collectorService)
    {
    }

    public function getRegistry(): CollectorRegistry
    {
        return $this->collectorService->getRegistry();
    }
}
