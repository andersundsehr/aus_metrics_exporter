<?php

declare(strict_types=1);


namespace AUS\AusMetricsExporter\Exporter;

use AUS\AusMetricsExporter\Configuration\Configuration;
use AUS\AusMetricsExporter\Service\CollectorService;
use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\Driver\Exception;

class MetricsExporter
{
    public function __construct(
        protected readonly Configuration $configuration,
        protected readonly CollectorService $collectorService,
    )
    {
    }

    /**
     * @throws Exception
     * @throws \Doctrine\DBAL\Exception
     * @throws DBALException
     */
    public function export(): bool|int
    {
        $fileContent = '';
        foreach ($this->collectorService->fetch() as $item) {
            $fileContent .= $item['identifier'] . ' ' .   $item['result'] . PHP_EOL;
        }

        return file_put_contents($this->configuration->getFilename(), $fileContent . PHP_EOL);
    }
}
