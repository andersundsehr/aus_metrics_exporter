<?php

declare(strict_types=1);

namespace AUS\AusMetricsExporter\Service;

use DateInterval;
use DateTime;
use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\Exception;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class CollectorService implements SingletonInterface
{
    public function collect(string $key, string $value): void
    {
        $tableName = 'tx_ausmetricsexporter_domain_model_metric';
        $connection = GeneralUtility::makeInstance(ConnectionPool::class)->getConnectionForTable($tableName);
        try {
            $connection->executeStatement(
                'REPLACE INTO ' . $tableName . '(identifier, result, stamp) VALUES(' . $connection->quote($key) . ',' . $connection->quote($value) . ", '" . (new DateTime())->format('Y-m-d H:i:s') . "')"
            );
        } catch (Exception) {
        }
    }

    /**
     * @throws \Doctrine\DBAL\Driver\Exception
     * @throws Exception|DBALException
     * @throws \Exception
     */
    public function fetch(): array
    {
        $tableName = 'tx_ausmetricsexporter_domain_model_metric';
        $connection = GeneralUtility::makeInstance(ConnectionPool::class)->getConnectionForTable($tableName);
        $qb = $connection->createQueryBuilder();

        // Stop collecting non-updated fields
        return $qb->select('*')
            ->from($tableName)
            ->where(
                $qb->expr()->gt(
                    'stamp',
                    $connection->quote((new DateTime())->sub(New DateInterval('PT5M'))->format('Y-M-d H:i:s'))
                )
            )
            ->executeQuery()
            ->fetchAllAssociative();
    }
}
