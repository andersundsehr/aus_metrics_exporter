<?php

declare(strict_types=1);

namespace AUS\MetricsExporter\Tests\Functional;

use AUS\MetricsExporter\Service\CollectorService;
use Prometheus\Exception\MetricsRegistrationException;
use Symfony\Component\Filesystem\Filesystem;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\TestingFramework\Core\Functional\Framework\Frontend\InternalRequest;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

class MetricsTest extends FunctionalTestCase
{
    protected array $testExtensionsToLoad = [
        'andersundsehr/metrics_exporter'
    ];

    protected function setUp(): void
    {
        $GLOBALS['EXEC_TIME'] = 1740476618;
        putenv('typo3DatabaseDriver=pdo_sqlite');
        parent::setUp();
        $this->importCSVDataSet(__DIR__ . '/../Fixtures/pages.csv');
        $this->copySiteConfiguration();
    }

    private function copySiteConfiguration(): void
    {
        $sourcePath = __DIR__ . '/../Fixtures/Sites/';
        // there the SiteConfiguration::getAllSiteConfigurationFromFiles it looks for our sites if it changes, check the path there
        $destinationPath = $this->instancePath . '/typo3conf/sites/default/';

        if (!is_dir($destinationPath)) {
            mkdir($destinationPath, 0777, true);
        }

        (new Filesystem())->copy(
            $sourcePath . 'config.yaml',
            $destinationPath . 'config.yaml',
            true
        );
    }

    /**
     * @test
     * @throws MetricsRegistrationException
     */
    public function testMetricsExportReturnsPrometheusFormattedGauge(): void
    {
        $this->setUpFrontendRootPage(1);

        $collectorService = GeneralUtility::makeInstance(CollectorService::class);
        $gauge = $collectorService->getRegistry()->getOrRegisterGauge('tx_metricsexporter', 'test', 'Checks if the exporter is working', ['label1', 'label2']);
        $gauge->set(123.0, ['value1', 'value2']);

        $request = (new InternalRequest());
        $request = $request->withMethod('GET')->withQueryParameter('type', '1717676395');

        $response = $this->executeFrontendSubRequest($request);

        // Verify the response was successful
        self::assertEquals(200, $response->getStatusCode());

        $responseBody = (string)$response->getBody();
        self::assertStringContainsString(<<<TEXT
# HELP tx_metricsexporter_test Checks if the exporter is working
# TYPE tx_metricsexporter_test gauge
tx_metricsexporter_test{label1="value1",label2="value2"} 123
TEXT
, $responseBody);
    }
}
