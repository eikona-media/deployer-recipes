<?php

/*
 * This file is part of EIKONA Media deployer recipe.
 *
 * (c) eikona-media.de
 *
 * @license MIT
 */

namespace Deployer;

use Deployer\Exception\ConfigurationException;

desc('Enable maintenance mode for previous release');
task(
    'maintenance:enable:previous_release',
    function () {
        if (has('previous_release')) {
            run('cd {{previous_release}} && {{bin/php}} {{bin/console}} lexik:maintenance:lock {{console_options}}');
        }
    }
)->setPrivate();

desc('Enable maintenance mode');
task(
    'maintenance:enable',
    function () {
        run('{{bin/php}} {{bin/console}} lexik:maintenance:lock {{console_options}}');
    }
)->setPrivate();

desc('Disable maintenance mode');
task(
    'maintenance:disable',
    function () {
        run('{{bin/php}} {{bin/console}} lexik:maintenance:unlock {{console_options}}');
    }
)->setPrivate();
