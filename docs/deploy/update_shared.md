# Update shared deploy helper

## Installing

Include helper in `deploy.php` file.

```php
require 'recipe/deploy/gitlab_ci.php';
```

### Usage

```php
  before('deploy:shared', 'deploy:update_shared_dirs');
```

```php
  before('deploy:shared', 'deploy:update_shared_dirs');
```

## Tasks & Configuration

* ``deploy:update_shared_dirs`` - updates shared dirs with config:
   * ``update_shared_dirs`` as array - default: ``[]``

  Copies all defined dirs from ``{{release_path}}/$dir`` to ``{{deploy_path}}/shared``, to transfer changes from repo to shared folders.

* ``deploy:update_shared_parameters`` - updates shared parameters for stage with config:

   * ``update_shared_parameters`` file path as string - default: ``''``

  Explodes the file path in ``$prefix`` and ``$extension`` - and builds a new path ``$fileStage = $prefix.'_{{stage}}.'.$extension;``

  Afterwards the from ``{{release_path}}/$fileStage`` will be copied to ``{{deploy_path}}/shared/{{update_shared_parameters}}``, to transfer changes of the stage from repo to shared file.
