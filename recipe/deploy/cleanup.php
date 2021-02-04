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

desc('Cleanup previous release');
task(
    'cleanup:previous:release',
    function () {
        if (has('previous_release')) {
            $sudo = get('clear_use_sudo') ? 'sudo' : '';
            foreach (array_filter(get('cleanup_previous_release_dirs')) as $dir) {
                run("$sudo rm -rf {{previous_release}}/$dir");
            }
        }
    }
)->setPrivate();
