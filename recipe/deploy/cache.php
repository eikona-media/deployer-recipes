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

desc('Clear status cache');
task(
    'deploy:cache_status_clear',
    function () {
        \clearstatcache(true);
    }
)->hidden();

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
)->hidden();

set('public_url', '');
set('opcache_webroot', 'web');
set('opcache_filename', 'opcache.php');

desc('Reset OPcache');
task(
    'deploy:opcache_reset',
    static function () {
        run(
            'cd {{release_path}} && echo "<?php opcache_reset();" > {{opcache_webroot}}/{{opcache_filename}} && curl -sL {{public_url}}/{{opcache_filename}} && rm {{opcache_webroot}}/{{opcache_filename}}'
        );
    }
)->hidden();
