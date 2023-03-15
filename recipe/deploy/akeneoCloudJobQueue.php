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

desc('Restart akeneo cloud job queue');
task('deploy:restart_akeneo_cloud_job_queue', function () {
    $param = \is_null(get('akeneo_cloud_queue')) ? get('akeneo_cloud_queue') : "";
    if ($param==='restart'){
        run('partners_systemctl pim_job_queue@* restart');
    }
})->hidden();

// always restart daemons after the symlink was changed
after('deploy:symlink', 'deploy:restart_akeneo_cloud_job_queue');
