<?php

/*
 * This file is part of EIKONA Media deployer recipe.
 *
 * (c) eikona-media.de
 *
 * @license MIT
 */

namespace Deployer;

require_once __DIR__.'/build/composer.php';

/*
 * Cycon Configuration
 */

// Cycon shared dirs
set(
    'shared_dirs',
    [
        '_temp',
        'log',
    ]
);

/*
 * Deploy task
 */
task(
    'deploy',
    [
        'deploy:info',
        'deploy:prepare',
        'deploy:lock',
        'deploy:release',
        'deploy:update_code',
        'deploy:clear_paths',
        'deploy:shared',
        'deploy:vendors',
        'deploy:writable',
        'deploy:symlink',
        'deploy:unlock',
        'cleanup',
    ]
)->desc('Deploy your project');

// Display success message on completion
after('deploy', 'success');

/*
 * Cycon build
 */
task('build', [
    'build:composer',
])->desc('Build your project');
