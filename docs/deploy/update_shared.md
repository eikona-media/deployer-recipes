# Update shared deploy helper

## Installing

Include helper in `deploy.php` file.

```php
require 'recipe/deploy/update_shared.php';
```

### Usage

```php
  before('deploy:shared', 'deploy:update_shared_dirs');
```

```php
  before('deploy:shared', 'deploy:update_shared_dirs');
```

```php
  before('deploy:update_shared_dirs', 'deploy:clear_shared_dirs');
```

## Tasks & Configuration

* `deploy:update_shared_dirs` - updates shared dirs with config:
   * `update_shared_dirs` as array - default: `[]`

  Copies all defined dirs from `{{release_path}}/$dir` to `{{deploy_path}}/shared`, to transfer changes from repo to shared folders.

* `deploy:update_shared_parameters` - updates shared parameters for stage with config:

   * `update_shared_parameters_target` file path as string - default: `''`
   * `update_shared_parameters_source` (optional) can preset `$fileSource` instead of generate from target
   * `update_shared_parameters_delete` (optional) can preset `$fileDelete` instead of generate from target

  Explodes the file path in `$prefix` and `$extension` - and builds a new path `$fileSource = $prefix.'_{{stage}}.'.$extension;`

  Afterwards the from `{{release_path}}/$fileSource` will be copied to `{{deploy_path}}/shared/{{update_shared_parameters_target}}`, to transfer changes of the stage from repo to shared file.

  Finally, all `$fileDelete = $prefix.'_*';` are deleted.

  >  Example for `.env` files (`.env.ci_{{stage}}`)
  >  ```php
  >  set('update_shared_parameters_target', '.env');
  >  set('update_shared_parameters_source', '.env.ci_{{stage}}');
  >  set('update_shared_parameters_delete', '.env.*');
  >  ```

* `clear:update_shared_dirs` - clear shared dirs with config:
  * `clear_shared_dirs` as array - default: `[]`

  Removes all defined dirs from `{{deploy_path}}/shared`, to cleanup shared folders before updating from repo.
