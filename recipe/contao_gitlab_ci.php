<?php

/*
 * This file is part of EIKONA deployer recipe.
 *
 * (c) eikona-media.de
 *
 * @license MIT
 */

namespace Deployer;

require_once __DIR__.'/contao.php';
require_once __DIR__.'/deploy/gitlab_ci.php';

// Symfony exclude dirs for upload
add(
    'exclude_dirs',
    [
        'app/config/parameters.*',
        'tests',
        'var',
        'web/bundles',
        'web/*dev.php',
    ]
);

// Contao exclude dirs for upload
add(
    'exclude_dirs',
    [
        'web/assets',
        'web/files',
        'web/share',
        'web/system',
    ]
);

// Contao update shared dirs + parameters from repo
before('deploy:shared', 'deploy:update_shared_dirs');
after('deploy:shared', 'deploy:update_shared_parameters');

// Contao database backup
add('shared_dirs', ['var/db_backups']);
before('deploy:symlink', 'contao:database:backup');
