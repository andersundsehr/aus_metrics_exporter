<?php

declare(strict_types=1);

namespace AUS\AusMetricsExporter\Task;

use AUS\AusIo\SubTask\AbstractSubTask;
use AUS\AusMetricsExporter\Configuration\Configuration;
use AUS\AusMetricsExporter\Exception\ExportException;
use AUS\AusMetricsExporter\Exporter\MetricsExporter;
use AUS\AusMetricsExporter\Service\CollectorService;
use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\Driver\Exception;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class ExportTask extends AbstractSubTask
{
    /**
     * @throws Exception
     * @throws DBALException
     * @throws \Doctrine\DBAL\Exception
     */
    public function execute(): void
    {
        /** @var MetricsExporter $metricsExporter */
        $metricsExporter = GeneralUtility::makeInstance(MetricsExporter::class, GeneralUtility::makeInstance(Configuration::class), GeneralUtility::makeInstance(CollectorService::class));
        if (!$metricsExporter->export()) {
            throw new ExportException('Could not export');
        }
    }
}
