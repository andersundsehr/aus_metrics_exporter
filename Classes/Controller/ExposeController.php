<?php

declare(strict_types=1);

namespace AUS\AusMetricsExporter\Controller;

use AUS\AusMetricsExporter\Exporter\MetricsExporter;
use TYPO3\CMS\Core\Http\StreamFactory;
use TYPO3\CMS\Core\Http\Response;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

class ExposeController extends ActionController
{
    public function __construct(protected MetricsExporter $metricsExporter)
    {
    }

    public function listAction(): Response
    {
        $text = $this->metricsExporter->export();
        $stream = GeneralUtility::makeInstance(StreamFactory::class)->createStream($text);
        return (new Response())->withBody($stream)->withHeader('Content-Type', 'text/plain; charset=utf-8')->withHeader('Content-Disposition', 'inline');
    }
}
