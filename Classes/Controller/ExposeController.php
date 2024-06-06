<?php

declare(strict_types=1);

namespace AUS\AusMetricsExporter\Controller;

use AUS\AusMetricsExporter\Exporter\MetricsExporter;
use Psr\Http\Message\StreamInterface;
use TYPO3\CMS\Core\Http\Response;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

class ExposeController extends ActionController
{
    public function listAction(Response $response, StreamInterface $stream, MetricsExporter $metricsExporter): Response
    {
        $stream->write($metricsExporter->export());
        return $response->withBody($stream);
    }
}
