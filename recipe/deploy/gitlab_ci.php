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

require_once __DIR__.'/scp.php';

add('exclude_paths', ['.gitlab-ci.yml']);

set('scp_upload_config', ['timeout' => null]);

desc('Upload code - git clone should be done by gitlab-ci');
task(
    'deploy:update_code',
    function () {
        set('scp_upload_source', './');
        set('scp_upload_destination', '{{release_path}}/');
        invoke('scp:upload:tar');
    }
)->hidden();

// always unlock, because otherwise retry with gitlab-ci won't work
after('deploy:failed', 'deploy:unlock');
