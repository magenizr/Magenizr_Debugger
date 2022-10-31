<?php
declare(strict_types=1);
/**
 * Magenizr Debugger
 *
 * @copyright   Copyright (c) 2018 - 2022 Magenizr (https://www.magenizr.com)
 * @license     https://www.magenizr.com/license Magenizr EULA
 */

\Magento\Framework\Component\ComponentRegistrar::register(
    \Magento\Framework\Component\ComponentRegistrar::MODULE,
    'Magenizr_Debugger',
    __DIR__
);
