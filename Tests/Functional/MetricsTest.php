<?php

declare(strict_types=1);

namespace AUS\AusMetricsExporter\Tests\Functional;

use AUS\AusMetricsExporter\Exporter\MetricsExporter;
use AUS\AusMetricsExporter\Service\CollectorService;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

class MetricsTest extends FunctionalTestCase
{
    protected array $testExtensionsToLoad = ['typo3conf/ext/aus_metrics_exporter'];

    /**
     * @test
     */
    public function suiteTest(): void
    {
        $collectorService = GeneralUtility::makeInstance(CollectorService::class);
        assert($collectorService instanceof CollectorService);
        $collectorService->collect('test_overwriting', '1.0', true);
        $collectorService->collect('test_adding', '0');
        $collectorService->collect('test_adding', '0.11');
        $collectorService->collect('test_adding', '13.94');


        $metricsExporter = GeneralUtility::makeInstance(MetricsExporter::class);
        assert($metricsExporter instanceof MetricsExporter);
        $text = $metricsExporter->export();

        $this->assertSame($text, <<<TEXT
test_overwriting 1.0
test_adding 14.0500000000000000

TEXT
        );
    }
}
