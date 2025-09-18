<?php

/** @noinspection PhpUnused */

declare(strict_types=1);

namespace AUS\MetricsExporter\EventListener;

use AUS\MetricsExporter\Factory\PdoFactory;
use AUS\MetricsExporter\Storage\PDO;
use TYPO3\CMS\Core\Database\Event\AlterTableDefinitionStatementsEvent;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class AlterTableDefinitionStatementsEventListener
{
    public function __construct(private readonly PdoFactory $pdoFactory)
    {
    }

    public function __invoke(AlterTableDefinitionStatementsEvent $event): void
    {
        $pdo = $this->pdoFactory->create();
        $pdoStorage = GeneralUtility::makeInstance(PDO::class, $pdo);
        $pdoStorage->createTables();
    }
}
