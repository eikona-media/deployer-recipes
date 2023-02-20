# Tar deploy helper

## Installing

Include helper in `deploy.php` file.

```php
require 'recipe/deploy/tar.php';
```

## Tasks & Configuration

* ``tar:create`` - creates a tar file with config:
    * ``local/bin/tar`` - default: ``which tar``
    * ``tar_create_options`` - default: ``cfzp``
    * ``tar_file_local`` - default: ``tempnam(sys_get_temp_dir(), 'deployer')``
    * ``exclude_paths`` as array converted to ``--exclude="%s"`` - default: ``['.git', '.gitignore', '.gitmodules', './deploy.php']``
    * ``tar_source`` - default: ``./``

* ``tar:extract`` - extracts a tar file with config:
    * ``tar_bin_host`` - default: ``which('tar')``
    * ``tar_extract_options`` - default: ``xfzop``
    * ``tar_file_host`` - default: ``get('deploy_path').'/'.basename(get('tar_file_local'))``
    * ``tar_destination`` - default: ``{{release_path}}/``

* ``tar:cleanup`` - deletes the ``tar_file_host`` and ``tar_file_local``
