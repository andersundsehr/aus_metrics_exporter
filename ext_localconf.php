<?php

use AUS\MetricsExporter\Controller\ExposeController;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

defined('TYPO3') || die();

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

        ExtensionManagementUtility::addTypoScriptSetup(
            '@import "EXT:metrics_exporter/Configuration/TypoScript/setup.typoscript"'
        );
    }
);
