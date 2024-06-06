<?php

use AUS\AusMetricsExporter\Controller\ExposeController;
use TYPO3\CMS\Core\Cache\Backend\SimpleFileBackend;
use TYPO3\CMS\Core\Cache\Frontend\VariableFrontend;
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

defined('TYPO3') || die();

$GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['ausmetricsexporter_cache'] = [
    'frontend' => VariableFrontend::class,
    'backend' => SimpleFileBackend::class,
];

call_user_func(
    static function (): void {
        ExtensionUtility::configurePlugin(
            'AusMetricsExpoter',
            'Expose',
            [
                ExposeController::class => 'list',
            ],
            [
                ExposeController::class => 'list',
            ]
        );
    }
);
