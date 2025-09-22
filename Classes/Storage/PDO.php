<?php

declare(strict_types=1);

namespace AUS\MetricsExporter\Storage;

class PDO extends \Prometheus\Storage\PDO
{
    /** @noinspection PhpMissingParentConstructorInspection */
    public function __construct(\PDO $database, string $prefix = 'tx_metricsexporter')
    {
        $this->database = $database;
        $this->prefix = $prefix;
    }

    public function createTables(): void
    {
        parent::createTables();
    }
}
