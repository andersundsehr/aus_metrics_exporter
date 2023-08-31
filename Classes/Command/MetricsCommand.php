<?php

declare(strict_types=1);

namespace AUS\AusMetricsExporter\Command;

use AUS\AusMetricsExporter\Exporter\MetricsExporter;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class MetricsCommand extends Command
{
    public function __construct(protected readonly MetricsExporter $metricsExporter)
    {
        parent::__construct('andersundsehr:metrics-exporter:export');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        return $this->metricsExporter->export() ? Command::SUCCESS : Command::FAILURE;
    }
}
