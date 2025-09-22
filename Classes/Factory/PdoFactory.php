<?php

declare(strict_types=1);

namespace AUS\MetricsExporter\Factory;

use PDO;
use RuntimeException;
use TYPO3\CMS\Core\Database\ConnectionPool;

class PdoFactory
{
    public function __construct(private readonly ConnectionPool $connectionPool)
    {
    }

    public function create(): PDO
    {
        $connectionPool = $this->connectionPool;
        $connectionParams = $connectionPool->getConnectionForTable('tx_metricsexporter_metadata')->getParams();

        $dsn = $this->buildDsn($connectionParams);

        $pdoOptions = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ];

        return new PDO(
            $dsn,
            $connectionParams['user'] ?? null,
            $connectionParams['password'] ?? null,
            $pdoOptions
        );
    }

    /**
     * @param array<string, mixed> $params
     */
    private function buildDsn(array $params): string
    {
        $driver = $params['driver'] ?? 'pdo_mysql';

        // Support mysqli and mysql as aliases for pdo_mysql
        if (in_array($driver, ['mysqli', 'mysql'], true)) {
            $driver = 'pdo_mysql';
        }

        return match ($driver) {
            'pdo_sqlite' => sprintf('sqlite:%s', $this->assertString($params['path'])),
            'pdo_mysql' => sprintf(
                'mysql:host=%s;dbname=%s;port=%s;charset=utf8mb4',
                $this->assertString($params['host']),
                $this->assertString($params['dbname']),
                isset($params['port']) ? $this->assertString($params['port']) : '3306',
            ),
            'pdo_pgsql' => sprintf(
                'pgsql:host=%s;dbname=%s%s',
                $this->assertString($params['host']),
                $this->assertString($params['dbname']),
                isset($params['port']) ? ';port=' . $this->assertString($params['port']) : ''
            ),
            default => throw new RuntimeException('Unsupported database driver: ' . $driver, 7157869870),
        };
    }

    private function assertString(mixed $value): string
    {
        if (!is_string($value)) {
            return '';
        }

        return $value;
    }
}
