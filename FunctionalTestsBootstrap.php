<?php

use TYPO3\TestingFramework\Core\Testbase;

(static function (): void {
        $testbase = new Testbase();
        $testbase->defineOriginalRootPath();
        // @phpstan-ignore-next-line
        $testbase->createDirectory(ORIGINAL_ROOT . 'typo3temp/var/tests');
        // @phpstan-ignore-next-line
        $testbase->createDirectory(ORIGINAL_ROOT . 'typo3temp/var/transient');
})();
