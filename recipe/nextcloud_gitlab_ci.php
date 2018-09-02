<?php

/*
 * This file is part of EIKONA deployer recipe.
 *
 * (c) eikona-media.de
 *
 * @license MIT
 */

namespace Deployer;

require_once __DIR__.'/nextcloud.php';
require_once __DIR__.'/deploy/gitlab_ci.php';

/*
 * Nextcloud Configuration
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

// Nextcloud update shared dirs + parameters from repo
before('deploy:shared', 'deploy:update_shared_dirs');
after('deploy:shared', 'deploy:update_shared_parameters');
