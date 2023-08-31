<?php

declare(strict_types=1);


namespace AUS\AusMetricsExporter\Configuration;

use TYPO3\CMS\Core\Core\Environment;

class Configuration
{
    public function getFilename(): string
    {
        return Environment::getPublicPath() . DIRECTORY_SEPARATOR . 'metrics.txt';
    }
}
