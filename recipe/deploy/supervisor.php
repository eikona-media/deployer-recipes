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

set(
    'bin/supervisorctl',
    function () {
        return run('which supervisorctl');
    }
);

desc('Restart supervisor daemons');
task('deploy:restart_supervisor_daemons', function () {
    $daemons = \is_array(get('supervisor_daemons')) ? get('supervisor_daemons') : [];
    foreach ($daemons as $daemon) {
        run('{{bin/supervisorctl}} restart '.$daemon);
    }
})->hidden();

// always restart daemons after the symlink was changed
after('deploy:symlink', 'deploy:restart_supervisor_daemons');
