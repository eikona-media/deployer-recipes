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
require_once __DIR__.'/build/composer.php';

/*
 * Bookstack Configuration
 */

// Bookstack shared files and dirs
set('shared_files', ['.env']);
set('shared_dirs', [
    'storage',
    'public/uploads',
]);

// Bookstack writeable dirs
set('writable_dirs', [
	'storage',
    'public/uploads',
]);

/*
 * Bookstack commands
 */
desc('Execute bookstack:migrate');
task('bookstack:migrate', function () {
    run('{{bin/php}} {{bin/artisan}} migrate');
});

desc('Execute bookstack:clear_cache');
task('bookstack:clear_cache', function () {
    run('{{bin/php}} {{bin/artisan}} cache:clear');
	run('{{bin/php}} {{bin/artisan}} config:clear');
	run('{{bin/php}} {{bin/artisan}} view:clear');
});

/*
 * Bookstack build
 */
desc('Build your project');
task('build', [
    'build:composer',
]);

// Bookstack exclude paths for upload
add(
    'exclude_paths',
    [
        './bootstrap/cache',
    ]
);

// Tasks
after('deploy:vendors', 'bookstack:migrate');
after('bookstack:migrate', 'bookstack:clear_cache');
