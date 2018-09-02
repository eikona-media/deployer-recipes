<?php

/*
 * This file is part of EIKONA deployer recipe.
 *
 * (c) eikona-media.de
 *
 * @license MIT
 */

namespace Deployer;

require_once __DIR__.'/roundcube.php';
require_once __DIR__.'/deploy/gitlab_ci.php';

/*
 * Roundcube Configuration
 */

// roundcube exclude paths
add(
    'exclude_paths',
    [
        '.tx',
        './bin',
        './SQL',
        './tests',
        './.travis.yml',
        './CHANGELOG',
        './composer.json-dist',
        './Dockerfile',
        './INSTALL',
        './LICENSE',
        './README.md',
        './UPGRADING',
    ]
);

// optionally add to deploy.php after setup:
//add('exclude_paths', ['./installer',]);

// Roundcube update shared parameters from repo
after('deploy:shared', 'deploy:update_shared_parameters');
