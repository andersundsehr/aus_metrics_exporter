<?php

declare(strict_types=1);

namespace AUS\MetricsExporter\Service;

use AUS\MetricsExporter\Factory\PdoFactory;
use AUS\MetricsExporter\Storage\PDO;
use Prometheus\CollectorRegistry;
use TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class CollectorService implements SingletonInterface
{
    private readonly CollectorRegistry $registry;

    public function __construct(private readonly PdoFactory $pdoFactory)
    {
        $pdo = $this->pdoFactory->create();
        $pdoStorage = GeneralUtility::makeInstance(PDO::class, $pdo);
        $this->registry = new CollectorRegistry($pdoStorage);
    }

    public function getRegistry(): CollectorRegistry
    {
        return $this->registry;
    }
}
