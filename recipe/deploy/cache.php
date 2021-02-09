<?php

/*
 * This file is part of EIKONA Media deployer recipe.
 *
 * (c) eikona-media.de
 *
 * @license MIT
 */

namespace Deployer;

desc('Clear status cache');
task(
    'deploy:cache_status_clear',
    function () {
        \clearstatcache(true);
    }
)->setPrivate();

desc('Clear accelerator cache');
task(
    'deploy:cache_accelerator_clear',
    function () {
        try {
            run('cd {{release_path}} && {{bin/composer}} show smart-core/accelerator-cache-bundle');
        } catch (\RuntimeException $e) {
            writeln("\r\033[1A\033[40C … skipped");

            /* @noinspection PhpUndefinedMethodInspection */
            output()->setWasWritten(false);

            return;
        }

        run('{{bin/php}} {{bin/console}} cache:accelerator:clear {{console_options}}');
    }
)->setPrivate();
