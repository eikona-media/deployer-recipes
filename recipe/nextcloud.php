<?php

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
 * Nextcloud Configuration
 */

// Nextcloud shared dirs
set('shared_dirs', ['apps']);

// Nextcloud shared files
set('shared_files', ['config/config.php']);

/*
 * Nextcloud update shared dirs + parameters from repo
 */
set('update_shared_dirs', ['apps']);
set('update_shared_parameters', 'config/config.php');
// optionally add to deploy.php:
//before('deploy:shared', 'deploy:update_shared_dirs');
//after('deploy:shared', 'deploy:update_shared_parameters');

/*
 * Upload with tar
 */

// Nextcloud exclude paths
add(
        'exclude_paths',
        [
            '.github',
            '.tx',
            './build',
            './contribute',
            './tests',
            './.user.ini',
            './autotest*.sh',
            './buildjsdocs.sh',
            './CHANGELOG.md',
            './CODE_OF_CONDUCT.md',
            './CONTRIBUTING.md',
            './COPYING',
            './COPYING-README',
            './issue_template.md',
            './README.md',
        ]
);

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
after('deploy', 'success');
