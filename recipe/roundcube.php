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

require_once __DIR__.'/deploy/update_shared.php';

/*
 * Roundcube Configuration
 */

// Roundcube shared dirs
set('shared_dirs', ['logs', 'temp']);

// Roundcube shared files
set('shared_files', ['config/config.inc.php']);

/*
 * Roundcube update shared parameters from repo
 */
set('update_shared_parameters_target', 'config/config.inc.php');
// optionally add to deploy.php:
//after('deploy:shared', 'deploy:update_shared_parameters');

/*
 * Upload with tar
 */

// Roundcube exclude paths
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

// optionally add to deploy.php after setup
//add('exclude_paths', ['./installer',]);

/*
 * Main task
 */
task('deploy', [
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
])->desc('Deploy your project');

// Display success message on completion
after('deploy', 'deploy:success');
