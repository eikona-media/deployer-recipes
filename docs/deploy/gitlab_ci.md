# Gitlab-CI deploy helper

## Installing

Include helper in `deploy.php` file.

```php
require 'recipe/deploy/gitlab_ci.php';
```

## Tasks & Configuration

Overwrites the following task:

* ``deploy:update_code`` - uploads a file via scp + tar with config:
    * ``scp_upload_source`` - default: ``./``
    * ``scp_upload_destination`` - default: ``{{release_path}}/``
    * invokes task ``scp:upload:tar``

Calls always ``deploy:unlock`` after ``deploy:failed``, because otherwise retry with gitlab-ci won't work.

Adds ``.gitlab-ci.yml`` to ``exclude_paths`` to ignore the file for upload.