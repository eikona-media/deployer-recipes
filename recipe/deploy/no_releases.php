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

use function Deployer\Support\str_contains;

desc('Preparing host for deploy without releases');
task('deploy:prepare', function () {
    // Check if shell is POSIX-compliant
    $result = run('echo $0');
    if (!str_contains($result, 'bash') && !str_contains($result, 'sh')) {
        throw new \RuntimeException('Shell on your server is not POSIX-compliant. Please change to sh, bash or similar.');
    }
    run('if [ ! -d {{deploy_path}} ]; then mkdir -p {{deploy_path}}; fi');

    // Create metadata .dep dir.
    run('cd {{deploy_path}} && if [ ! -d .dep ]; then mkdir .dep; fi');
});

set('release_path', function () {
    return get('deploy_path');
});

task('deploy:release', static function () {
    // Create no release folder.
});

task('deploy:shared', static function () {
    // Shared are not necessary without releases
});

task('deploy:symlink', static function () {
    // Symlink is not necessary without releases
});

task('cleanup', static function () {
    // Cleanup is not necessary without releases
});

task('rollback', static function () {
    // Cleanup is not possible without releases
});
