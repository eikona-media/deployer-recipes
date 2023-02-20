<?php
declare(strict_types=1);

/*
 * This file is part of EIKONA Media deployer recipe.
 *
 * (c) eikona-media.de
 *
 * @license MIT
 */

namespace Deployer;

use Deployer\Exception\RunException;

desc('Enable maintenance mode for previous release');
task(
    'maintenance:enable:previous_release',
    function () {
        if (has('previous_release')) {
            try {
                run('cd {{previous_release}} && {{bin/php}} {{bin/console}} contao:maintenance-mode enable {{console_options}}');
                return;
            } catch (RunException) {
            }
            run('cd {{previous_release}} && {{bin/php}} {{bin/console}} lexik:maintenance:lock {{console_options}}');
        }
    }
)->hidden();

desc('Enable maintenance mode');
task(
    'maintenance:enable',
    function () {
        try {
            run('{{bin/php}} {{bin/console}} contao:maintenance-mode enable {{console_options}}');
            return;
        } catch (RunException) {
        }
        run('{{bin/php}} {{bin/console}} lexik:maintenance:lock {{console_options}}');
    }
)->hidden();

desc('Disable maintenance mode');
task(
    'maintenance:disable',
    function () {
        try {
            run('{{bin/php}} {{bin/console}} contao:maintenance-mode disable {{console_options}}');
            return;
        } catch (RunException) {
        }
        run('{{bin/php}} {{bin/console}} lexik:maintenance:unlock {{console_options}}');
    }
)->hidden();
