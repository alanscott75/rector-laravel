<?php

declare(strict_types=1);

use AlanScott75\RectorLaravel\Rector\MethodCall\AppMakeToAppHelperRector;

use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../../../../../config/config.php');

    $rectorConfig->rule(AppMakeToAppHelperRector::class);
};
