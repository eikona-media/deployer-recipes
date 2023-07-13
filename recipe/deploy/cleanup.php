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

desc('Cleanup previous release');
task(
    'cleanup:previous_release',
    function () {
        if (has('previous_release')) {
            $sudo = get('clear_use_sudo') ? 'sudo' : '';
            $rmcmd = get('clear_use_rmdir') ? 'rmdir --ignore-fail-on-non-empty' : 'rm -rf';
            foreach (array_filter(get('cleanup_previous_release_dirs')) as $dir) {
                run("$sudo $rmcmd {{previous_release}}/$dir");
            }
        }
    }
)->hidden();
