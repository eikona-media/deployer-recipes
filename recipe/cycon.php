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
desc('Deploy your project');
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
);

// Display success message on completion
after('deploy', 'deploy:success');

/*
 * Cycon build
 */
desc('Build your project');
task('build', [
    'build:composer',
]);
