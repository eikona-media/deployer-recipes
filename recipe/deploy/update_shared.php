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

desc('Update shared dirs from repo');
task(
    'deploy:update_shared_dirs',
    function () {
        $sharedPath = '{{deploy_path}}/shared';

        foreach (array_filter(get('update_shared_dirs')) as $dir) {
            // Create shared dir if it does not exist.
            run("mkdir -p $sharedPath/$dir");

            // If release contains shared dir, copy that dir from release to shared.
            if (test("[ -d $(echo {{release_path}}/$dir) ]")) {
                run("cp -rv {{release_path}}/$dir $sharedPath/".\dirname(parse($dir)));
            }
        }
    }
)->hidden();

desc('Clear shared dirs');
task(
    'deploy:clear_shared_dirs',
    function () {
        $sharedPath = '{{deploy_path}}/shared';
        foreach (array_filter(get('clear_shared_dirs')) as $dir) {
            // Remove shared dir.
            run("rm -rf $sharedPath/$dir");
        }
    }
)->hidden();
